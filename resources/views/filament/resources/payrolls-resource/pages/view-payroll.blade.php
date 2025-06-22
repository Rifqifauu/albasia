<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Kolom Kiri: Informasi Pegawai & Periode -->
        <div class="grid md:grid-cols-2 gap-4">
            <!-- Informasi Pegawai -->
            <div class="rounded-lg bg-white p-4 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Informasi Pegawai</h3>
                <dl class="text-xs space-y-1">
                    <div class="flex justify-between"><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $record->employee->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Bagian</dt><dd class="font-medium">{{ $record->employee->bagian }}</dd></div>
                    <div class="pt-3 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <dt class="text-[11px] text-gray-600 dark:text-gray-300 font-bold uppercase">Total Penerimaan</dt>
                        <dd class="text-lg font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ 'Rp ' . number_format($record->penerimaan, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Periode & Kehadiran -->
            <div class="rounded-lg bg-white p-4 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Periode & Kehadiran</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                    <div><dt class="text-gray-500">Mulai Dari</dt><dd class="font-medium">{{ $record->periode_awal }}</dd></div>
                    <div><dt class="text-gray-500">Sampai Dengan</dt><dd class="font-medium">{{ $record->periode_akhir }}</dd></div>
                    <div><dt class="text-gray-500">Jumlah Hari</dt><dd class="font-medium">{{ $record->jumlah_hari }}</dd></div>
                    <div><dt class="text-gray-500">Lembur</dt><dd class="font-medium">{{ $record->jumlah_lembur }} Jam</dd></div>
                    <div><dt class="text-gray-500">Kurang</dt><dd class="font-medium">{{ $record->kekurangan_jam }} Jam</dd></div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: 3 Komponen Upah dalam 1 Grid -->
        <div class="grid md:grid-cols-3 gap-4">
            <!-- Komponen Upah -->
            <div class="rounded-lg bg-white p-4 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Upah</h3>
                <dl class="text-xs space-y-1">
                    <div class="flex justify-between"><dt class="text-gray-500">Upah Pokok</dt><dd class="font-semibold">{{ 'Rp ' . number_format($record->upah, 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tunjangan</dt><dd class="font-semibold">{{ 'Rp ' . number_format($record->tunjangan, 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Premi</dt><dd class="font-semibold">{{ 'Rp ' . number_format($record->premi_kehadiran, 0, ',', '.') }}</dd></div>
                </dl>
            </div>

            <!-- Potongan -->
            <div class="rounded-lg bg-white p-4 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Potongan</h3>
                <dl class="text-xs space-y-1">
                    <div class="flex justify-between"><dt class="text-gray-500">BPJS</dt><dd class="font-semibold text-red-600">{{ 'Rp ' . number_format($record->potongan_bpjs, 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Jam Kurang</dt><dd class="font-semibold text-red-600">{{ 'Rp ' . number_format($record->potongan_kekurangan_jam, 0, ',', '.') }}</dd></div>
                </dl>
            </div>

            <!-- Total Komponen -->
            <div class="rounded-lg bg-white p-4 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Total</h3>
                <dl class="text-xs space-y-1">
                    <div class="flex justify-between"><dt class="text-gray-500">T. Upah</dt><dd class="font-semibold text-blue-600">{{ 'Rp ' . number_format($record->total_upah, 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">T. Lembur</dt><dd class="font-semibold text-blue-600">{{ 'Rp ' . number_format($record->total_lembur, 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">T. Tunjangan</dt><dd class="font-semibold text-blue-600">{{ 'Rp ' . number_format($record->total_tunjangan, 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">T. Premi</dt><dd class="font-semibold text-blue-600">{{ 'Rp ' . number_format($record->total_premi_kehadiran, 0, ',', '.') }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
</x-filament-panels::page>
