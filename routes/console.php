<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('iib:go-live-check', function () {
    $this->info('Iniciando validacao de go-live...');

    $ok = true;

    if (empty(config('app.key'))) {
        $this->error('APP_KEY ausente. Rode: php artisan key:generate');
        $ok = false;
    } else {
        $this->line('APP_KEY: ok');
    }

    try {
        DB::select('SELECT 1');
        $this->line('Banco de dados: ok');
    } catch (Throwable $e) {
        $this->error('Banco de dados indisponivel: '.$e->getMessage());
        $ok = false;
    }

    $sessionDriver = config('session.driver');
    $cacheDriver = config('cache.default');

    if ($sessionDriver !== 'file') {
        $this->warn('SESSION_DRIVER atual: '.$sessionDriver.' (recomendado: file)');
        $ok = false;
    } else {
        $this->line('SESSION_DRIVER=file: ok');
    }

    if ($cacheDriver !== 'file') {
        $this->warn('CACHE_STORE atual: '.$cacheDriver.' (recomendado: file)');
        $ok = false;
    } else {
        $this->line('CACHE_STORE=file: ok');
    }

    $paths = [
        storage_path('framework/cache'),
        storage_path('framework/sessions'),
        storage_path('framework/views'),
        storage_path('logs'),
        base_path('bootstrap/cache'),
    ];

    foreach ($paths as $path) {
        if (! File::exists($path)) {
            File::ensureDirectoryExists($path);
        }

        if (! is_writable($path)) {
            $this->error('Sem permissao de escrita: '.$path);
            $ok = false;
        }
    }

    $admin = User::query()->where('email', env('ADMIN_EMAIL', 'admin@institutointeligencia.com.br'))->first();
    if (! $admin) {
        $this->warn('Admin nao encontrado no banco. Rode: php artisan db:seed --class=AdminUserSeeder --force');
        $ok = false;
    } elseif (! $admin->is_active) {
        $this->warn('Admin encontrado, mas inativo.');
        $ok = false;
    } else {
        $this->line('Admin ativo: ok');
    }

    if ($ok) {
        $this->info('Validacao concluida com sucesso.');
        return self::SUCCESS;
    }

    $this->error('Validacao concluida com pendencias.');
    return self::FAILURE;
})->purpose('Valida ambiente de hospedagem para go-live (MySQL/Apache2 sem Redis)');

Artisan::command('iib:bootstrap-prod', function () {
    $this->info('Executando bootstrap de producao...');

    $steps = [
        'optimize:clear',
        'migrate --force',
        'db:seed --force',
        'config:cache',
        'route:cache',
        'view:cache',
    ];

    foreach ($steps as $step) {
        $this->line('> php artisan '.$step);
        $code = Artisan::call($step);

        if ($code !== 0) {
            $this->error('Falha no passo: '.$step);
            $this->line(Artisan::output());
            return self::FAILURE;
        }

        $output = trim(Artisan::output());
        if ($output !== '') {
            $this->line($output);
        }
    }

    $this->info('Bootstrap de producao concluido com sucesso.');
    return self::SUCCESS;
})->purpose('Executa bootstrap de producao (migrate, seed e caches)');

Artisan::command('iib:import-postgres {--section=all : Seção a importar: all|core|registry|mailing|stats|users} {--truncate : Limpa tabelas de destino antes de importar}', function () {
    $pgHost = (string) env('PGSRC_HOST', '');
    $pgPort = (string) env('PGSRC_PORT', '5432');
    $pgDb = (string) env('PGSRC_DATABASE', '');
    $pgUser = (string) env('PGSRC_USERNAME', '');
    $pgPassword = (string) env('PGSRC_PASSWORD', '');
    $pgSslMode = (string) env('PGSRC_SSLMODE', 'prefer');

    if ($pgHost === '' || $pgDb === '' || $pgUser === '' || $pgPassword === '') {
        $this->error('Defina PGSRC_HOST, PGSRC_DATABASE, PGSRC_USERNAME e PGSRC_PASSWORD no .env');
        return self::FAILURE;
    }

    $dockerCheck = new Process(['docker', '--version']);
    $dockerCheck->run();
    if (! $dockerCheck->isSuccessful()) {
        $this->error('Docker nao disponivel para usar o cliente psql.');
        return self::FAILURE;
    }

    $this->info('Conectando ao Postgres de origem e preparando importacao para MySQL...');

    $section = strtolower(trim((string) $this->option('section')));
    $allowedSections = ['all', 'core', 'registry', 'mailing', 'stats', 'users'];
    if (! in_array($section, $allowedSections, true)) {
        $this->error('Section invalida. Use: all|core|registry|mailing|stats|users');
        return self::FAILURE;
    }

    $selectedSections = $section === 'all' ? ['core', 'registry', 'mailing', 'stats', 'users'] : [$section];
    if (array_intersect($selectedSections, ['registry', 'mailing', 'stats']) !== []) {
        $selectedSections[] = 'core';
    }
    $selectedSections = array_values(array_unique($selectedSections));

    $tablesBySection = [
        'core' => ['expert_types', 'survey_waves'],
        'registry' => ['registry_expert_waves', 'registry_experts'],
        'mailing' => ['mailing_contact_waves', 'mailing_contacts'],
        'stats' => ['import_file_stats', 'import_runs'],
        'users' => ['users'],
    ];

    $selectedTables = [];
    foreach ($selectedSections as $selectedSection) {
        $selectedTables = array_merge($selectedTables, $tablesBySection[$selectedSection] ?? []);
    }
    $selectedTables = array_values(array_unique($selectedTables));

    $fetchRows = function (string $query) use ($pgHost, $pgPort, $pgDb, $pgUser, $pgPassword, $pgSslMode): array {
        $wrapped = "SELECT COALESCE(json_agg(t), '[]'::json) FROM ({$query}) t";

        $proc = new Process([
            'docker', 'run', '--rm',
            '--add-host', 'host.docker.internal:host-gateway',
            '-e', 'PGPASSWORD='.$pgPassword,
            'postgres:16-alpine',
            'psql',
            '-h', $pgHost,
            '-p', $pgPort,
            '-U', $pgUser,
            '-d', $pgDb,
            '--set', 'sslmode='.$pgSslMode,
            '-At',
            '-c', $wrapped,
        ]);

        $proc->setTimeout(300);
        $proc->run();

        if (! $proc->isSuccessful()) {
            throw new RuntimeException(trim($proc->getErrorOutput()) ?: 'Falha ao consultar Postgres.');
        }

        $json = trim($proc->getOutput());
        if ($json === '') {
            return [];
        }

        $decoded = json_decode($json, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('Retorno invalido do Postgres ao decodificar JSON.');
        }

        return $decoded;
    };

    $normalizePassword = function (?string $legacyPassword): string {
        $legacyPassword = trim((string) $legacyPassword);

        if ($legacyPassword !== '' && str_starts_with($legacyPassword, '$2y$')) {
            return $legacyPassword;
        }

        return Hash::make((string) env('ADMIN_PASSWORD', 'changeme'));
    };

    try {
        if ($this->option('truncate')) {
            $this->warn('Limpando tabelas de destino...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach (array_reverse($selectedTables) as $table) {
                DB::table($table)->delete();
                try {
                    DB::statement('ALTER TABLE '.$table.' AUTO_INCREMENT = 1');
                } catch (Throwable) {
                    // algumas tabelas podem nao ter auto_increment; ignora.
                }
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $typeMap = [];
        $waveMap = [];
        $expertMap = [];
        $mailingMap = [];
        $runMap = [];

        if (in_array('core', $selectedSections, true)) {
            $this->line('Importando tipos...');
            foreach ($fetchRows('SELECT id, slug, name, sort_order, created_at FROM expert_types ORDER BY sort_order, slug') as $row) {
                $existing = DB::table('expert_types')->where('slug', $row['slug'])->first();
                if ($existing) {
                    DB::table('expert_types')->where('id', $existing->id)->update([
                        'name' => $row['name'],
                        'sort_order' => (int) ($row['sort_order'] ?? 0),
                    ]);
                    $typeMap[$row['id']] = (int) $existing->id;
                } else {
                    $id = DB::table('expert_types')->insertGetId([
                        'slug' => $row['slug'],
                        'name' => $row['name'],
                        'sort_order' => (int) ($row['sort_order'] ?? 0),
                        'created_at' => $row['created_at'] ?? now(),
                    ]);
                    $typeMap[$row['id']] = (int) $id;
                }
            }

            $this->line('Importando ondas...');
            foreach ($fetchRows('SELECT id, year, wave, label, created_at FROM survey_waves ORDER BY year, wave') as $row) {
                $existing = DB::table('survey_waves')
                    ->where('year', (int) $row['year'])
                    ->where('wave', (int) $row['wave'])
                    ->first();

                if ($existing) {
                    DB::table('survey_waves')->where('id', $existing->id)->update([
                        'label' => $row['label'],
                    ]);
                    $waveMap[$row['id']] = (int) $existing->id;
                } else {
                    $id = DB::table('survey_waves')->insertGetId([
                        'year' => (int) $row['year'],
                        'wave' => (int) $row['wave'],
                        'label' => $row['label'],
                        'created_at' => $row['created_at'] ?? now(),
                    ]);
                    $waveMap[$row['id']] = (int) $id;
                }
            }
        }

        if (in_array('registry', $selectedSections, true)) {
            $this->line('Importando especialistas...');
            foreach ($fetchRows('SELECT * FROM registry_experts ORDER BY created_at, id') as $row) {
                $payload = [
                    'expert_type_id' => isset($row['expert_type_id']) && isset($typeMap[$row['expert_type_id']]) ? $typeMap[$row['expert_type_id']] : null,
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'company' => $row['company'] ?? null,
                    'occupation' => $row['occupation'] ?? null,
                    'address' => $row['address'] ?? null,
                    'city' => $row['city'] ?? null,
                    'postal_code' => $row['postal_code'] ?? null,
                    'phone' => $row['phone'] ?? null,
                    'email' => $row['email'] ?? null,
                    'registration_wave_id' => isset($row['registration_wave_id']) && isset($waveMap[$row['registration_wave_id']]) ? $waveMap[$row['registration_wave_id']] : null,
                    'registration_wave_note' => $row['registration_wave_note'] ?? null,
                    'is_active' => (bool) ($row['is_active'] ?? true),
                    'deactivated_at' => $row['deactivated_at'] ?? null,
                    'created_at' => $row['created_at'] ?? now(),
                    'updated_at' => $row['updated_at'] ?? now(),
                ];

                $existing = null;
                if (! empty($payload['email'])) {
                    $existing = DB::table('registry_experts')->where('email', $payload['email'])->first();
                }

                if (! $existing) {
                    $existing = DB::table('registry_experts')
                        ->where('first_name', $payload['first_name'])
                        ->where('last_name', $payload['last_name'])
                        ->whereRaw('COALESCE(company, "") = COALESCE(?, "")', [$payload['company']])
                        ->whereRaw('COALESCE(city, "") = COALESCE(?, "")', [$payload['city']])
                        ->first();
                }

                if ($existing) {
                    DB::table('registry_experts')->where('id', $existing->id)->update($payload);
                    $expertMap[$row['id']] = (int) $existing->id;
                } else {
                    $id = DB::table('registry_experts')->insertGetId($payload);
                    $expertMap[$row['id']] = (int) $id;
                }
            }
        }

        if (in_array('registry', $selectedSections, true)) {
            $this->line('Importando vinculos especialistas x ondas...');
            foreach ($fetchRows('SELECT expert_id, wave_id, created_at FROM registry_expert_waves') as $row) {
                if (! isset($expertMap[$row['expert_id']]) || ! isset($waveMap[$row['wave_id']])) {
                    continue;
                }

                DB::table('registry_expert_waves')->insertOrIgnore([
                    'expert_id' => $expertMap[$row['expert_id']],
                    'wave_id' => $waveMap[$row['wave_id']],
                    'created_at' => $row['created_at'] ?? now(),
                ]);
            }
        }

        if (in_array('mailing', $selectedSections, true)) {
            $this->line('Importando mailing...');
            foreach ($fetchRows('SELECT * FROM mailing_contacts ORDER BY created_at, id') as $row) {
                $payload = [
                    'interviewee_name' => $row['interviewee_name'],
                    'company' => $row['company'] ?? null,
                    'occupation' => $row['occupation'] ?? null,
                    'city' => $row['city'] ?? null,
                    'linkedin_url' => $row['linkedin_url'] ?? null,
                    'company_website' => $row['company_website'] ?? null,
                    'merco_approval_status' => $row['merco_approval_status'] ?? null,
                    'is_active' => (bool) ($row['is_active'] ?? true),
                    'deactivated_at' => $row['deactivated_at'] ?? null,
                    'created_at' => $row['created_at'] ?? now(),
                    'updated_at' => $row['updated_at'] ?? now(),
                ];

                $existing = DB::table('mailing_contacts')
                    ->where('interviewee_name', $payload['interviewee_name'])
                    ->whereRaw('COALESCE(company, "") = COALESCE(?, "")', [$payload['company']])
                    ->whereRaw('COALESCE(city, "") = COALESCE(?, "")', [$payload['city']])
                    ->first();

                if ($existing) {
                    DB::table('mailing_contacts')->where('id', $existing->id)->update($payload);
                    $mailingMap[$row['id']] = (int) $existing->id;
                } else {
                    $id = DB::table('mailing_contacts')->insertGetId($payload);
                    $mailingMap[$row['id']] = (int) $id;
                }
            }
        }

        if (in_array('mailing', $selectedSections, true)) {
            $this->line('Importando vinculos mailing x ondas...');
            foreach ($fetchRows('SELECT mailing_contact_id, wave_id, source_file, created_at FROM mailing_contact_waves') as $row) {
                if (! isset($mailingMap[$row['mailing_contact_id']]) || ! isset($waveMap[$row['wave_id']])) {
                    continue;
                }

                DB::table('mailing_contact_waves')->insertOrIgnore([
                    'mailing_contact_id' => $mailingMap[$row['mailing_contact_id']],
                    'wave_id' => $waveMap[$row['wave_id']],
                    'source_file' => $row['source_file'] ?? null,
                    'created_at' => $row['created_at'] ?? now(),
                ]);
            }
        }

        if (in_array('stats', $selectedSections, true)) {
            $this->line('Importando historico de importacoes...');
            foreach ($fetchRows('SELECT * FROM import_runs ORDER BY started_at, id') as $row) {
                $id = DB::table('import_runs')->insertGetId([
                    'source_dir' => $row['source_dir'] ?? '',
                    'started_at' => $row['started_at'] ?? now(),
                    'finished_at' => $row['finished_at'] ?? null,
                    'notes' => $row['notes'] ?? null,
                ]);

                $runMap[$row['id']] = (int) $id;
            }

            foreach ($fetchRows('SELECT * FROM import_file_stats ORDER BY processed_at, id') as $row) {
                if (! isset($runMap[$row['run_id']])) {
                    continue;
                }

                DB::table('import_file_stats')->insert([
                    'run_id' => $runMap[$row['run_id']],
                    'file_name' => $row['file_name'] ?? '',
                    'category' => $row['category'] ?? 'unknown',
                    'type_slug' => $row['type_slug'] ?? null,
                    'year' => isset($row['year']) ? (int) $row['year'] : null,
                    'wave' => isset($row['wave']) ? (int) $row['wave'] : null,
                    'inserted_count' => (int) ($row['inserted_count'] ?? 0),
                    'processed_at' => $row['processed_at'] ?? now(),
                ]);
            }
        }

        if (in_array('users', $selectedSections, true)) {
            $this->line('Importando usuarios admin...');
            foreach ($fetchRows('SELECT name, email, password_hash, role, is_active, email_verified_at, created_at, updated_at FROM users') as $row) {
                if (empty($row['email'])) {
                    continue;
                }

                DB::table('users')->updateOrInsert(
                    ['email' => strtolower(trim((string) $row['email']))],
                    [
                        'name' => $row['name'] ?? 'Administrador',
                        'password' => $normalizePassword($row['password_hash'] ?? null),
                        'role' => $row['role'] ?? 'admin',
                        'is_active' => (bool) ($row['is_active'] ?? true),
                        'email_verified_at' => $row['email_verified_at'] ?? null,
                        'created_at' => $row['created_at'] ?? now(),
                        'updated_at' => $row['updated_at'] ?? now(),
                    ]
                );
            }
        }

        $this->info('Importacao Postgres -> MySQL concluida com sucesso.');
        return self::SUCCESS;
    } catch (Throwable $e) {
        $this->error('Falha na importacao: '.$e->getMessage());
        return self::FAILURE;
    }
})->purpose('Importa dados do Postgres legado para o MySQL atual usando psql via Docker');
