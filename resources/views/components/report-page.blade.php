@props([
    'title',
    'subtitle' => null,
    'badge' => 'Relatorio',
    'countLabel' => 'Registros',
    'count' => 0,
])

@php
    $formattedCount = number_format((int) $count, 0, ',', '.');
@endphp

<x-filament-panels::page>
    <div class="space-y-8">
        <section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-[linear-gradient(135deg,#f8fafc_0%,#eef2ff_48%,#f8fafc_100%)] p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] sm:p-8 lg:p-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(56,189,248,0.22),transparent_28%),radial-gradient(circle_at_bottom_left,rgba(251,191,36,0.18),transparent_24%)]"></div>
            <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-cyan-400 via-sky-500 to-amber-400"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.32em] text-slate-600 shadow-sm">
                            {{ $badge }}
                        </div>

                        <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Consulta pronta
                        </div>
                    </div>

                    <h1 class="mt-5 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                        {{ $title }}
                    </h1>

                    @if ($subtitle)
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">
                            {{ $subtitle }}
                        </p>
                    @endif

                    @isset($meta)
                        <div class="mt-6 flex flex-wrap gap-3 rounded-2xl border border-white/70 bg-white/75 p-3 shadow-sm backdrop-blur">
                            {{ $meta }}
                        </div>
                    @endisset
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[320px] lg:grid-cols-1">
                    <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm backdrop-blur">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.32em] text-slate-500">{{ $countLabel }}</div>
                        <div class="mt-2 text-4xl font-semibold tracking-tight text-slate-950">{{ $formattedCount }}</div>
                        <div class="mt-2 text-sm text-slate-500">Itens visiveis nesta pagina.</div>
                    </div>

                    <div class="rounded-2xl border border-cyan-200 bg-cyan-50/80 p-4 shadow-sm">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.32em] text-cyan-700">Painel</div>
                        <div class="mt-2 text-sm leading-6 text-slate-700">Leitura otimizada para comparar registros, vinculos e origem dos dados.</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_18px_60px_rgba(15,23,42,0.08)]">
            <div class="border-b border-slate-200 bg-slate-50/90 px-5 py-4 sm:px-6">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.32em] text-slate-500">Tabela</h2>
                        <p class="mt-1 text-sm text-slate-600">Linhas recuperadas da importacao e dos cadastros vinculados.</p>
                    </div>

                    <div class="text-sm font-medium text-slate-500">
                        Visualizacao consolidada
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border-separate border-spacing-0 text-sm">
                    <thead class="sticky top-0 z-10 bg-white/95 text-slate-600 backdrop-blur">
                        <tr>
                            {{ $thead }}
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-filament-panels::page>