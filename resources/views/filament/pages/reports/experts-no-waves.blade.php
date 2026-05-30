<x-report-page
    title="Especialistas sem vinculo de ondas"
    subtitle="Especialistas cadastrados que ainda nao foram associados a uma onda."
    badge="Relatorios de especialistas"
    count-label="Especialistas"
    :count="$rows->count()"
>
    <x-slot:meta>
        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs text-slate-200">Filtro: sem vinculo</span>
    </x-slot:meta>

    <x-slot:thead>
        <th class="px-5 py-4 text-left font-semibold">Nome</th>
        <th class="px-5 py-4 text-left font-semibold">Tipo</th>
        <th class="px-5 py-4 text-left font-semibold">Empresa</th>
        <th class="px-5 py-4 text-left font-semibold">Cidade</th>
    </x-slot:thead>

    @forelse($rows as $row)
        <tr class="transition hover:bg-slate-50/80">
            <td class="px-5 py-4 font-medium text-slate-900">{{ $row->first_name }} {{ $row->last_name }}</td>
            <td class="px-5 py-4 text-slate-600">{{ $row->expertType->name ?? '-' }}</td>
            <td class="px-5 py-4 text-slate-600">{{ $row->company ?? '-' }}</td>
            <td class="px-5 py-4 text-slate-600">{{ $row->city ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-5 py-10 text-center text-slate-500">Sem dados.</td>
        </tr>
    @endforelse
</x-report-page>
