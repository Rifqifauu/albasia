{{-- resources/views/components/data-table-collapsible.blade.php --}}
@php
    $groupedItems = collect($items)->groupBy('grade');
@endphp

<div class="space-y-4" x-data="{ openGrades: {} }">
    @forelse ($groupedItems as $grade => $gradeItems)
        <div class="border rounded-lg overflow-hidden">
            {{-- Grade Header - Clickable --}}
            <div
                class="bg-blue-100 px-4 py-3 border-b cursor-pointer hover:bg-blue-200 transition-colors"
                @click="openGrades['{{ $grade }}'] = !openGrades['{{ $grade }}']"
                x-init="openGrades['{{ $grade }}'] = true"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-blue-800">Grade {{ $grade }}</h3>
                        <div class="text-sm text-blue-600 mt-1">
                            Total: {{ number_format($gradeItems->sum('total_jumlah')) }} pcs |
                            {{ number_format($gradeItems->sum('total_volume'), 3) }} m³
                        </div>
                    </div>
                    <div class="text-blue-600">
                        <svg
                            class="w-5 h-5 transform transition-transform duration-200"
                            :class="{ 'rotate-180': openGrades['{{ $grade }}'] }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Data Table - Collapsible --}}
            <div
                x-show="openGrades['{{ $grade }}']"
                x-collapse
                x-cloak
            >
                <table class="table-auto w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left border-b font-medium text-gray-700">Tebal</th>
                            <th class="px-3 py-2 text-right border-b font-medium text-gray-700">Jumlah</th>
                            <th class="px-3 py-2 text-right border-b font-medium text-gray-700">Volume (m³)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gradeItems as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 border-b">{{ $item->tebal ?? '-' }}</td>
                                <td class="px-3 py-2 text-right border-b">{{ number_format($item->total_jumlah ?? 0) }}</td>
                                <td class="px-3 py-2 text-right border-b">{{ number_format($item->total_volume ?? 0, 3) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="border rounded-lg p-8 text-center text-gray-500">

            <p class="text-lg font-medium">Tidak ada data</p>
            <p class="text-sm mt-1">Belum ada data yang tersedia untuk ditampilkan</p>
        </div>
    @endforelse
</div>
