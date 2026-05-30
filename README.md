# IIB Cadastro

Aplicacao administrativa para cadastro e analise de especialistas e mailing, com relatorios por onda e importacao de dados legados.

## Stack

- PHP 8.2+
- Laravel 12.61
- Filament 4 (painel admin em /admin)
- MySQL (destino principal)
- Tailwind CSS 4 + Vite
- Sessao e cache com driver file

## Escopo funcional

- Autenticacao admin no Filament
- Cadastro de tipos de especialistas
- Cadastro de especialistas
- Cadastro de mailing
- Cadastro de ondas
- Vinculos N:N
  - especialistas x ondas
  - mailing x ondas
- Relatorios no painel
  - Especialistas Onda 1
  - Especialistas Onda 2
  - Especialistas Ambas Ondas
  - Especialistas Sem Vinculo
  - Mailing Onda 1
  - Mailing Onda 2
  - Mailing Ambas Ondas
  - Mailing Sem Vinculo
- Resumo de importacao
- Importacao de planilhas (XLSX)
- Importacao de base legada Postgres -> MySQL

## Requisitos locais

- PHP 8.2 ou superior
- Composer
- Node.js + npm
- MySQL
- Docker (apenas para o comando de importacao Postgres)

## Setup rapido

1. Instalar dependencias:

	composer install
	npm install

2. Criar arquivo de ambiente e chave:

	cp .env.example .env
	php artisan key:generate

3. Configurar MySQL no .env:

	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=iib_cadastro
	DB_USERNAME=iib_user
	DB_PASSWORD=change_me

4. Rodar migrations e seed:

	php artisan migrate
	php artisan db:seed

5. Subir aplicacao:

	php artisan serve

6. (Opcional) Frontend em desenvolvimento:

	npm run dev

## Credenciais de admin

Definidas por variaveis no .env:

- ADMIN_EMAIL
- ADMIN_PASSWORD
- ADMIN_NAME

Valores padrao no .env.example:

- ADMIN_EMAIL=admin@institutointeligencia.com.br
- ADMIN_PASSWORD=changeme
- ADMIN_NAME=Administrador IIB

## URLs locais

- App: http://127.0.0.1:8000
- Admin: http://127.0.0.1:8000/admin

## Comandos customizados

### 1) Validacao de go-live

Valida chave de app, conexao com banco, drivers de sessao/cache e usuario admin ativo.

php artisan iib:go-live-check

### 2) Bootstrap de producao

Executa limpeza de caches, migrate, seed e recompilacao de caches.

php artisan iib:bootstrap-prod

### 3) Importacao Postgres -> MySQL

Comando:

php artisan iib:import-postgres --section=all --truncate

Opcoes:

- --section=all|core|registry|mailing|stats|users
- --truncate (limpa tabelas de destino da secao selecionada antes de importar)

Dependencias de secao:

- core: expert_types, survey_waves
- registry: registry_experts, registry_expert_waves
- mailing: mailing_contacts, mailing_contact_waves
- stats: import_runs, import_file_stats
- users: users

Observacao importante:

- O importador consulta Postgres via cliente psql executado em container Docker (imagem postgres:16-alpine).
- Isso evita dependencia de pdo_pgsql no PHP local.

## Variaveis para origem Postgres

Configurar no .env para usar importacao legada:

- PGSRC_HOST
- PGSRC_PORT (padrao 5432)
- PGSRC_DATABASE
- PGSRC_USERNAME
- PGSRC_PASSWORD
- PGSRC_SSLMODE (padrao prefer)

## Deploy (Apache2)

1. Apontar VirtualHost para a pasta public.
2. Habilitar mod_rewrite.
3. Garantir permissao de escrita em storage e bootstrap/cache.
4. Executar:

	php artisan iib:bootstrap-prod

## Troubleshooting rapido

### Erro no login: This password does not use the Bcrypt algorithm

Causa comum: senha de usuario importada em hash legado nao compatavel.

Estado atual do projeto:

- O importador de usuarios normaliza senha para Bcrypt.
- Se existir usuario antigo inconsistente, regrave senha com Hash::make.

### Erro de importacao: There is no active transaction

Causa historica: uso de TRUNCATE em transacao longa no MySQL.

Estado atual do projeto:

- Importador nao depende de transacao global.
- Limpeza usa DELETE (com reset de AUTO_INCREMENT quando aplicavel).

### Falha de conexao MySQL em hospedagem

Se o banco so estiver acessivel por tunel SSH:

- Ajuste DB_HOST e DB_PORT para o endpoint local do tunel (exemplo: 127.0.0.1:3308).

## Qualidade e verificacao

Comandos uteis:

- php artisan test
- php artisan optimize:clear
- php artisan view:cache

## Arquivos de referencia

- Comandos artisan customizados: routes/console.php
- Resumo de importacao: app/Filament/Pages/ImportSummary.php
- Views de relatorio: resources/views/filament/pages/reports/
- Base de conhecimento da sessao: CONHECIMENTO-SESSAO-2026-05-30.md
