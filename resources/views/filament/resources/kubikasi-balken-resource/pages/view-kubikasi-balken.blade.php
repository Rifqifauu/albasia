<x-filament::page>
    <div class="space-y-6">
        {{-- Header Card --}}
        <x-filament::card>
            <div class="md:flex justify-between items-start gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-primary-800 dark:text-white">
                        Detail Kubikasi Balken
                    </h2>
                    <p class="text-sm text-primary-700 dark:text-primary-400 mt-1">
                        Rincian data tally berdasarkan grade
                    </p>
                </div>

                <div class="mt-4 md:mt-0 text-sm text-gray-600 dark:text-gray-300">
                    <div>
                        <span>Nomor Polisi:</span><br>
                        <span class="text-lg font-semibold text-primary-800 dark:text-white">
                            {{ $nomor_polisi }}
                        </span>
                    </div>
                    <div class="mt-2">
                        <span>Tanggal Tally:</span><br>
                        <span class="text-lg font-semibold text-primary-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </x-filament::card>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-filament::card>
                <div class="text-center space-y-1">
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                        Total Jumlah Balken
                    </p>
                    <p class="text-3xl font-bold text-primary-800 dark:text-white">
                        {{ number_format($total_jumlah) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">balken</p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center space-y-1">
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                        Total Volume
                    </p>
                    <p class="text-3xl font-bold text-success-700 dark:text-success-400">
                        {{ number_format($total_volume, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">m³</p>
                </div>
            </x-filament::card>
        </div>

        {{-- Detail Table --}}
        <x-filament::card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-primary-800 dark:text-primary-200">Grade</th>
                            <th class="px-4 py-3 text-right  dark:text-primary-500">Jumlah</th>
                            <th class="px-4 py-3 text-right  dark:text-primary-500">Volume</th>
                
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($pallets as $pallet)
                            <tr class="hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full
                                            {{ $pallet->grade === 'KOTAK' ? 'bg-info-700' : '' }}
                                            {{ $pallet->grade === 'ON GRADE' ? 'bg-success-700' : '' }}
                                            {{ $pallet->grade === 'ALL GRADE' ? 'bg-primary-700' : '' }}
                                            {{ $pallet->grade === 'DS 4' ? 'bg-warning-700' : '' }}
                                            {{ $pallet->grade === 'AFKIR' ? 'bg-danger-700' : '' }}">
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
                                    {{ number_format($pallet->total_volume, 2) }}
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
        </x-filament::card>

        {{-- Back Button --}}
        <div class="flex justify-start mt-6">
            <x-filament::button 
                tag="a" 
                href="{{ route('filament.admin.resources.kubikasi-balkens.index') }}"
                color="primary"
                icon="heroicon-o-arrow-left"
            >
                Kembali ke Daftar
            </x-filament::button>
        </div>
    </div>
</x-filament::page>
