<x-filament-panels::page>
    <div x-data="{ tab: 'stok-log' }">
        <div class="flex flex-wrap gap-2 border-b mb-4">
            <button
                class="px-4 py-2 text-sm font-medium"
                :class="{ 'border-b-2 border-primary-600 text-primary-600': tab === 'stok-log' }"
                @click="tab = 'stok-log'"
            >
                Stok Log
            </button>
            <button
                class="px-4 py-2 text-sm font-medium"
                :class="{ 'border-b-2 border-primary-600 text-primary-600': tab === 'stok-balken' }"
                @click="tab = 'stok-balken'"
            >
                Stok Balken
            </button>
            <button
                class="px-4 py-2 text-sm font-medium"
                :class="{ 'border-b-2 border-primary-600 text-primary-600': tab === 'produksi-log' }"
                @click="tab = 'produksi-log'"
            >
                Produksi Log
            </button>
            <button
                class="px-4 py-2 text-sm font-medium"
                :class="{ 'border-b-2 border-primary-600 text-primary-600': tab === 'produksi-balken' }"
                @click="tab = 'produksi-balken'"
            >
                Produksi Balken
            </button>
        </div>

        {{-- TAB: STOK LOG --}}
        <div x-show="tab === 'stok-log'" x-cloak>
            <h2 class="text-lg font-bold">STOK LOG</h2>
            @include('components.data-table', ['items' => $logStok])
        </div>

        {{-- TAB: STOK BALKEN --}}
        <div x-show="tab === 'stok-balken'" x-cloak>
            <h2 class="text-lg font-bold">STOK BALKEN</h2>
            @include('components.data-table', ['items' => $balkenStok])
        </div>

        {{-- TAB: PRODUKSI LOG --}}
        <div x-show="tab === 'produksi-log'" x-cloak>
            <h2 class="text-lg font-bold">LOG MASUK PRODUKSI</h2>
            @include('components.data-table', ['items' => $logProduksi])
        </div>

        {{-- TAB: PRODUKSI BALKEN --}}
        <div x-show="tab === 'produksi-balken'" x-cloak>
            <h2 class="text-lg font-bold">BALKEN MASUK PRODUKSI</h2>
            @include('components.data-table', ['items' => $balkenProduksi])
        </div>
    </div>
</x-filament-panels::page>
