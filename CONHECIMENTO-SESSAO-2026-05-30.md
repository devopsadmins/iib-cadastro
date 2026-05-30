# IIB Cadastro - Base de Conhecimento da Sessao

Data: 2026-05-30
Escopo: consolidacao tecnica da migracao, correcoes e operacao do projeto

## 1) Objetivo do projeto

Migrar o fluxo legado para uma aplicacao Laravel + Filament + MySQL, mantendo cadastro, relatorios e importacao de dados historicos com operacao viavel em hospedagem com acesso restrito ao banco.

## 2) Contexto de origem e destino

### Origem (legado)
- Projeto: iib-sistema
- Stack: Astro SSR + PostgreSQL + Redis + RabbitMQ + Docker Swarm
- Dados de interesse: especialistas, mailing, ondas, historico de importacao e usuarios

### Destino (novo)
- Projeto: iib-cadastro
- Stack: Laravel 12.61.0 + Filament 4 + MySQL + Tailwind
- Runtime observado: PHP 8.4.20 (tambem testado com PHP 8.3 no ciclo anterior)
- Sessao/cache ajustados para ambiente sem Redis (drivers em arquivo)

## 3) Estrutura funcional implementada

### Dominio principal
- Tipos de especialistas
- Ondas de pesquisa
- Especialistas
- Mailing
- Vinculos N:N (especialista x onda, mailing x onda)
- Historico de importacao (runs e estatisticas por arquivo)

### UI administrativa
- CRUDs no Filament para entidades principais
- Pagina de resumo de importacao
- Paginas de relatorio por recorte:
  - Especialistas: onda 1, onda 2, ambas, sem vinculo
  - Mailing: onda 1, onda 2, ambas, sem vinculo

## 4) Problemas criticos encontrados e como foram resolvidos

### 4.1) Login quebrando com erro de hash
Erro:
- RuntimeException: This password does not use the Bcrypt algorithm

Causa:
- Usuario admin estava com senha salva em formato nao Bcrypt (vinda de importacao legada)

Correcao:
- Normalizacao da senha no importador de usuarios:
  - Mantem hash apenas se for Bcrypt ($2y$)
  - Caso contrario, grava Hash::make(ADMIN_PASSWORD)
- Normalizacao imediata do usuario admin existente no banco para hash Bcrypt

Impacto:
- Filament voltou a autenticar corretamente no fluxo padrao Laravel

### 4.2) Falha de importacao com transacao
Erro:
- There is no active transaction

Causa:
- Uso de TRUNCATE dentro de transacao global (MySQL faz commit implicito)

Correcao:
- Remocao da transacao global longa do importador
- Limpeza com DELETE em vez de TRUNCATE
- Reset de AUTO_INCREMENT com tolerancia a tabelas sem incremento automatico

Impacto:
- Eliminada a quebra de rollback/commit no final da importacao

### 4.3) Necessidade de importar por area
Necessidade:
- Rodar importacao parcial (sem executar tudo)

Correcao:
- Opcao adicionada no comando: --section=
- Valores suportados:
  - all
  - core
  - registry
  - mailing
  - stats
  - users
- Dependencia automatica de core quando necessario (registry, mailing, stats)

Impacto:
- Processo de migracao mais controlado e facil de depurar

## 5) Comandos operacionais relevantes

### Importacao Postgres -> MySQL
- php artisan iib:import-postgres --section=all --truncate
- php artisan iib:import-postgres --section=core --truncate
- php artisan iib:import-postgres --section=registry --truncate
- php artisan iib:import-postgres --section=mailing --truncate
- php artisan iib:import-postgres --section=stats --truncate
- php artisan iib:import-postgres --section=users --truncate

### Verificacao de ambiente
- php artisan iib:go-live-check

### Bootstrap de producao
- php artisan iib:bootstrap-prod

### Cache de views (apos alteracoes de interface)
- php artisan view:cache

## 6) Observacoes de infraestrutura e acesso a banco

- Destino MySQL operando via tunel SSH em configuracao local (exemplo observado: 127.0.0.1:3308)
- Fonte Postgres lida via psql em container docker (imagem postgres:16-alpine)
- Ambiente local sem pdo_pgsql no PHP nativo, por isso o importador usa cliente externo via Docker para consultas de origem

## 7) Arquivos-chave alterados na sessao

- routes/console.php
  - comando iib:import-postgres refatorado
  - opcao --section adicionada
  - limpeza segura sem TRUNCATE
  - normalizacao de senha importada

- resources/views/components/report-page.blade.php
  - componente visual compartilhado para relatorios

- resources/views/filament/pages/import-summary.blade.php
  - redesign completo do resumo de importacao

- resources/views/filament/pages/reports/*.blade.php
  - padronizacao visual usando componente compartilhado

## 8) Padroes e decisoes tecnicas consolidadas

- Autenticacao segue hash Bcrypt/Laravel; nao persistir hash legado desconhecido no campo password
- Evitar transacoes gigantes em importacao longa com multiplas tabelas e DDL/limpeza
- Preferir importacao por etapas (core -> registry -> mailing -> users -> stats) para previsibilidade
- Em ambiente com restricao de rede ao MySQL, operar por tunel local e validar host/porta no .env

## 9) Checklist rapido de recuperacao

1. Conferir .env de destino (DB_*) e origem (PGSRC_*)
2. Rodar: php artisan optimize:clear
3. Rodar importacao por secao
4. Confirmar login admin
5. Rodar: php artisan view:cache
6. Validar relatorios no painel

## 10) Riscos residuais e recomendacoes

- Se houver hashes nao-Bcrypt remanescentes em users, o login pode voltar a falhar
- Se o tunel SSH cair, importacao/operacoes de DB falham por indisponibilidade
- Recomenda-se criar job/command dedicado para auditoria de hashes de usuarios e healthcheck de conexao com timeout explicito

---
Documento criado para preservar o conhecimento da sessao e reduzir retrabalho em futuras manutencoes.
