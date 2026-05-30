<x-filament-panels::page>
    <h2 class="text-lg font-semibold mb-3">Especialistas vinculados em ambas as ondas</h2>
    <div class="rounded-lg border border-gray-200 bg-white p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="py-2 text-left">Nome</th>
                    <th class="py-2 text-left">Tipo</th>
                    <th class="py-2 text-left">Empresa</th>
                    <th class="py-2 text-left">Cidade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr class="border-b">
                        <td class="py-2">{{ $row->first_name }} {{ $row->last_name }}</td>
                        <td class="py-2">{{ $row->expertType->name ?? '-' }}</td>
                        <td class="py-2">{{ $row->company ?? '-' }}</td>
                        <td class="py-2">{{ $row->city ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-3 text-gray-500">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
