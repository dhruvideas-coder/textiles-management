<div class="p-2 sm:p-4 border rounded-xl bg-white shadow-sm mt-4 overflow-hidden">
    <h3 class="text-base sm:text-lg font-bold mb-3 text-gray-800">Measurement Grid (6x12)</h3>
    
    <div class="overflow-x-auto -mx-2 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <table class="min-w-full divide-y divide-gray-200 border-collapse text-[10px] sm:text-sm">
                <thead>
                    <tr>
                        <th class="border bg-gray-50 px-0.5 py-1.5 text-center text-gray-600 font-bold min-w-[30px] sticky left-0 z-10">#</th>
                        @for ($c = 1; $c <= 6; $c++)
                        <th class="border bg-gray-50 px-1 py-1.5 text-center text-gray-600 font-bold min-w-[60px]">
                            <span class="sm:hidden">C{{ $c }}</span>
                            <span class="hidden sm:inline">Col {{ $c }}</span>
                        </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @for ($r = 1; $r <= 12; $r++)
                    <tr>
                        <td class="border bg-gray-100 px-0.5 py-1.5 font-semibold text-center sticky left-0 z-10">{{ $r }}</td>
                        @for ($c = 1; $c <= 6; $c++)
                        <td class="border px-0 py-0">
                            <input type="number" 
                                   step="0.01" 
                                   wire:model.live="grid.{{ $r }}.{{ $c }}" 
                                   class="w-full text-center border-0 focus:ring-1 focus:ring-primary-500 text-[10px] sm:text-sm p-1.5 h-8 sm:h-auto" 
                                   placeholder="0">
                        </td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold text-gray-700">
                        <td class="border px-0.5 py-1.5 text-center sticky left-0 z-10">Σ</td>
                        @for ($c = 1; $c <= 6; $c++)
                        <td class="border px-1 py-1.5 text-center">{{ number_format($column_totals[$c] ?? 0, 1) }}</td>
                        @endfor
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-2 sm:flex sm:space-x-4">
        <div class="px-2 py-2 sm:px-4 sm:py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg">
            <span class="block text-[10px] font-semibold uppercase opacity-75">Pieces</span>
            <span class="text-base sm:text-2xl font-bold">{{ $total_pieces }}</span>
        </div>
        <div class="px-2 py-2 sm:px-4 sm:py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            <span class="block text-[10px] font-semibold uppercase opacity-75">Total Mtrs</span>
            <span class="text-base sm:text-2xl font-bold">{{ number_format($total_meters, 1) }}</span>
        </div>
    </div>
</div>
