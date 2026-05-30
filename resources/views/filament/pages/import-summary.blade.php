<x-filament-panels::page>
    @php
        $statsCollection = collect($stats);
        $filesCount = $statsCollection->count();
        $totalInserted = $statsCollection->sum('inserted_count');
        $categoriesCount = $statsCollection->pluck('category')->filter()->unique()->count();
        $hasRun = (bool) $latestRun;
    @endphp

    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-[linear-gradient(135deg,#0f172a_0%,#111827_52%,#172554_100%)] p-6 text-white shadow-[0_24px_80px_rgba(15,23,42,0.22)] sm:p-8 lg:p-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(56,189,248,0.26),transparent_28%),radial-gradient(circle_at_left,rgba(250,204,21,0.14),transparent_24%)]"></div>
            <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-cyan-400 via-sky-500 to-amber-400"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.32em] text-cyan-200">
                        Importacao
                    </div>

                    <h1 class="mt-5 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">Import Summary</h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
                        Visao consolidada da ultima execucao, com origem, periodo e total processado por arquivo.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3 rounded-2xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-medium text-slate-200">
                            {{ $hasRun ? 'Execucao encontrada' : 'Sem execucao registrada' }}
                        </span>
                        @if($latestRun)
                            <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-100">
                                Origem: {{ $latestRun->source_dir }}
                            </span>
                            <span class="rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1 text-xs font-medium text-cyan-100">
                                Inicio: {{ $latestRun->started_at }}
                            </span>
                            <span class="rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-xs font-medium text-amber-100">
                                Fim: {{ $latestRun->finished_at ?? 'em andamento' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[340px] lg:grid-cols-1">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.32em] text-slate-300">Arquivos</div>
                        <div class="mt-2 text-4xl font-semibold tracking-tight">{{ number_format($filesCount, 0, ',', '.') }}</div>
                    </div>

                    <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 p-4 backdrop-blur">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.32em] text-emerald-200">Inseridos</div>
                        <div class="mt-2 text-4xl font-semibold tracking-tight">{{ number_format($totalInserted, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Categorias</div>
                <div class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($categoriesCount, 0, ',', '.') }}</div>
                <p class="mt-2 text-sm text-slate-500">Agrupamentos de importacao presentes na ultima execucao.</p>
            </div>

            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Inicio</div>
                <div class="mt-3 text-lg font-semibold text-slate-900">{{ $latestRun?->started_at ?? 'Sem dados' }}</div>
                <p class="mt-2 text-sm text-slate-500">Momento em que a importacao foi iniciada.</p>
            </div>

            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Fim</div>
                <div class="mt-3 text-lg font-semibold text-slate-900">{{ $latestRun?->finished_at ?? 'Em andamento' }}</div>
                <p class="mt-2 text-sm text-slate-500">Ultima marcacao registrada para a execucao.</p>
            </div>
        </div>

        <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_18px_60px_rgba(15,23,42,0.08)]">
            <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Arquivos importados</h2>
                        <p class="mt-1 text-sm text-slate-600">Detalhamento por arquivo, tipo e onda.</p>
                    </div>
                    <div class="text-sm text-slate-500">Ultima execucao consolidada</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border-separate border-spacing-0 text-sm">
                    <thead class="sticky top-0 z-10 bg-white/95 text-slate-600 backdrop-blur">
                        <tr>
                            <th class="border-b border-slate-200 px-5 py-4 text-left font-semibold uppercase tracking-[0.18em]">Arquivo</th>
                            <th class="border-b border-slate-200 px-5 py-4 text-left font-semibold uppercase tracking-[0.18em]">Categoria</th>
                            <th class="border-b border-slate-200 px-5 py-4 text-left font-semibold uppercase tracking-[0.18em]">Tipo</th>
                            <th class="border-b border-slate-200 px-5 py-4 text-left font-semibold uppercase tracking-[0.18em]">Ano</th>
                            <th class="border-b border-slate-200 px-5 py-4 text-left font-semibold uppercase tracking-[0.18em]">Onda</th>
                            <th class="border-b border-slate-200 px-5 py-4 text-left font-semibold uppercase tracking-[0.18em]">Inseridos</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($stats as $row)
                            <tr class="border-b border-slate-100 transition hover:bg-slate-50/80">
                                <td class="px-5 py-4 font-medium text-slate-900">{{ $row->file_name }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">
                                        {{ $row->category }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $row->type_slug ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $row->year ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $row->wave ?? '-' }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-900">{{ number_format((int) $row->inserted_count, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-500">Sem dados para mostrar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-filament-panels::page>
