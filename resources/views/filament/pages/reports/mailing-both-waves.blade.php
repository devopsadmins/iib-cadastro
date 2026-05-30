<x-report-page
    title="Mailing vinculado em ambas as ondas"
    subtitle="Contatos que aparecem nas duas ondas do material de pesquisa."
    badge="Relatorios de mailing"
    count-label="Contatos"
    :count="$rows->count()"
>
    <x-slot:meta>
        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs text-slate-200">Filtro: ambas as ondas</span>
    </x-slot:meta>

    <x-slot:thead>
        <th class="px-5 py-4 text-left font-semibold">Entrevistado</th>
        <th class="px-5 py-4 text-left font-semibold">Empresa</th>
        <th class="px-5 py-4 text-left font-semibold">Cargo</th>
        <th class="px-5 py-4 text-left font-semibold">Cidade</th>
    </x-slot:thead>

    @forelse($rows as $row)
        <tr class="transition hover:bg-slate-50/80">
            <td class="px-5 py-4 font-medium text-slate-900">{{ $row->interviewee_name }}</td>
            <td class="px-5 py-4 text-slate-600">{{ $row->company ?? '-' }}</td>
            <td class="px-5 py-4 text-slate-600">{{ $row->occupation ?? '-' }}</td>
            <td class="px-5 py-4 text-slate-600">{{ $row->city ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-5 py-10 text-center text-slate-500">Sem dados.</td>
        </tr>
    @endforelse
</x-report-page>
