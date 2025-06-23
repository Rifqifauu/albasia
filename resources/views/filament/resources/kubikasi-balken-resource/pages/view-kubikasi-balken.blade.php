<x-filament::card>
<x-filament::card>
{{-- Header --}}
<div class="md:flex justify-between items-start gap-4">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-primary-800 dark:text-white">
            Detail Kubikasi Balken
        </h2>
        <p class="text-sm text-primary-700 dark:text-primary-400 mt-1">
            Rincian data tally berdasarkan grade
        </p>
    </div>

    <div class="mt-4 md:mt-0 flex flex-wrap gap-6 text-sm text-gray-600 dark:text-gray-300">
        <div>
            <span>Nomor Polisi:</span><br>
            <span class="text-lg font-semibold text-primary-800 dark:text-white">
                {{ $nomor_polisi }}
            </span>
        </div>

        <div>
            <span>Tanggal Tally:</span><br>
            <span class="text-lg font-semibold text-primary-800 dark:text-white">
                {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
            </span>
        </div>

        
    </div>
</div>


    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <x-filament::card class="rounded-xl shadow-sm">
            <div class="text-center space-y-1">
                <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                    Total Jumlah Balken
                </p>
                <p class="text-3xl font-bold text-gray-800 dark:text-gray-300">
                    {{ number_format($total_jumlah) }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">balken</p>
            </div>
        </x-filament::card>

        <x-filament::card class="rounded-xl shadow-sm">
            <div class="text-center space-y-1">
                <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                    Total Tagihan
                </p>
                <p class="text-3xl font-bold text-success-700 dark:text-info-500">
                {{ 'Rp ' . number_format($total_tagihan, 0) }}

                </p>
            </div>
        </x-filament::card>
    </div>
</x-filament::card>




    {{-- Tab Section --}}
    <div x-data="{ tab: 'dimensi' }" class="mt-6">
        {{-- Tab Navigation --}}
        
        <div class="flex space-x-4 border-b border-gray-200 dark:border-gray-700 mb-4">
              <button 
                x-on:click="tab = 'dimensi'" 
                :class="tab === 'dimensi' ? 'border-primary-600 text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-300'" 
                class="px-4 py-2 border-b-2 font-semibold transition-all"
            >
                Detail per Dimensi
            </button>
            <button 
                x-on:click="tab = 'grade'" 
                :class="tab === 'grade' ? 'border-primary-600 text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-300'" 
                class="px-4 py-2 border-b-2 font-semibold transition-all"
            >
                Ringkasan per Grade
            </button>
          
        </div>

        {{-- Tab: Grade Summary --}}
        <div x-show="tab === 'grade'" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-primary-800 dark:text-primary-200">Grade</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Jumlah</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Volume</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Harga <span class="text-xs">/cm3</span></th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Total Harga </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    @foreach ($pallets as $pallet)
        @php
            $grade = strtoupper($pallet->grade);
            $hargaPerCm = $cost[$grade]->harga ?? 0;
            $totalHarga = $pallet->total_volume * $hargaPerCm;
        @endphp
        <tr class="hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-colors">
            <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full
                        {{ $pallet->grade === 'kotak' ? 'bg-info-700' : '' }}
                        {{ $pallet->grade === 'ongrade' ? 'bg-success-700' : '' }}
                        {{ $pallet->grade === 'allgrade' ? 'bg-primary-700' : '' }}
                        {{ $pallet->grade === 'ds4' ? 'bg-warning-700' : '' }}
                        {{ $pallet->grade === 'afkir' ? 'bg-danger-700' : '' }}">
                    </div>
                    <span class="font-medium text-primary-900 dark:text-white">
                        {{ $pallet->grade }}
                    </span>
                </div>
            </td>
            <td class="px-4 py-3 text-right text-primary-900 dark:text-white">
                {{ number_format($pallet->total_jumlah) }}
            </td>
            <td class="px-4 py-3 text-right text-primary-900 dark:text-white">
                {{ number_format($pallet->total_volume) }} cm3
            </td>
            <td class="px-4 py-3 text-right text-primary-900 dark:text-white">
                {{ 'Rp ' . number_format($hargaPerCm, 2, ',', '.') }}
            </td>
            <td class="px-4 py-3 text-right text-primary-900 dark:text-white">
                {{ 'Rp ' . number_format($totalHarga, 2, ',', '.') }}
            </td>
        </tr>
    @endforeach
</tbody>

                    <tfoot class="bg-primary-50 dark:bg-primary-900/20 font-bold">
                        <tr>
                            <td class="px-4 py-3 text-primary-900 dark:text-white">TOTAL</td>
                            <td class="px-4 py-3 text-right text-primary-900 dark:text-white">
                                {{ number_format($total_jumlah) }}
                            </td>
                            <td class="px-4 py-3 text-right text-primary-900 dark:text-white">
                                {{ number_format($total_volume, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Tab: Dimensi Detail --}}
        <div x-show="tab === 'dimensi'" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-primary-800 dark:text-primary-200">Grade</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Tebal</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Lebar</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Panjang</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Jumlah</th>
                            <th class="px-4 py-3 text-right dark:text-primary-500">Total Volume</th>
                            
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
@foreach ($this->detailPallets as $detail)
                            <tr class="hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-colors">
                                <td class="px-4 py-3 text-primary-900 dark:text-white">{{ $detail->grade }}</td>
                                <td class="px-4 py-3 text-right text-primary-900 dark:text-white">{{ $detail->tebal }}</td>
                                <td class="px-4 py-3 text-right text-primary-900 dark:text-white">{{ $detail->lebar }}</td>
                                <td class="px-4 py-3 text-right text-primary-900 dark:text-white">{{ $detail->panjang }}</td>
                                                                                                <td class="px-4 py-3 text-right text-primary-900 dark:text-white">{{ number_format($detail->total_jumlah) }}</td>

                                <td class="px-4 py-3 text-right text-primary-900 dark:text-white">{{ number_format($detail->total_volume) }} cm3</td>

               </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
    {{ $this->detailPallets->appends(['tab' => 'dimensi'])->links() }}
</div>

            </div>
        </div>
    </div>
</x-filament::card>
