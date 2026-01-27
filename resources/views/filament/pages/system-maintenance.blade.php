<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Maintenance Mode Card -->
            <x-filament::section>
                <x-slot name="heading">Mode Pemeliharaan</x-slot>
                <x-slot name="description">Kendalikan akses publik ke website.</x-slot>

                <div class="space-y-4 pt-4">
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/10">
                        <div>
                            <p class="text-sm font-bold">Status Sekarang</p>
                            @if($this->isMaintenanceMode())
                                <span
                                    class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 mt-1">
                                    <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                                    AKTIF (MAINTENANCE)
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 mt-1">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    AKTIF (PUBLIC)
                                </span>
                            @endif
                        </div>

                        <div>
                            @if($this->isMaintenanceMode())
                                <x-filament::button wire:click="toggleMaintenanceMode" color="success"
                                    icon="heroicon-o-check-circle">
                                    Matikan Maintenance
                                </x-filament::button>
                            @else
                                <x-filament::button wire:click="toggleMaintenanceMode" color="danger"
                                    icon="heroicon-o-stop-circle">
                                    Nyalakan Maintenance
                                </x-filament::button>
                            @endif
                        </div>
                    </div>

                    @if($this->isMaintenanceMode())
                        <div
                            class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-900/30 rounded-lg">
                            <p class="text-xs text-amber-800 dark:text-amber-400 flex items-start gap-2">
                                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="size-4 shrink-0" />
                                <span>Website sedang ditutup untuk umum. Gunakan link di bawah ini untuk melihat website
                                    sebagai admin:</span>
                            </p>
                            <a href="{{ url('/' . $this->bypassSecret) }}" target="_blank"
                                class="text-xs font-mono font-bold text-primary hover:underline mt-2 block break-all">
                                {{ url('/' . $this->bypassSecret) }}
                            </a>
                        </div>
                    @endif
                </div>
            </x-filament::section>

            <!-- Optimization Card -->
            <x-filament::section>
                <x-slot name="heading">Optimasi & Cache</x-slot>
                <x-slot name="description">Bersihkan cache sistem untuk memuat perubahan terbaru.</x-slot>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                    <x-filament::button wire:click="clearCache" color="warning" icon="heroicon-o-trash" class="w-full">
                        Bersihkan Cache
                    </x-filament::button>
                    <x-filament::button wire:click="optimize" color="info" icon="heroicon-o-bolt" class="w-full">
                        Jalankan Optimasi
                    </x-filament::button>
                    <x-filament::button wire:click="linkStorage" color="gray" icon="heroicon-o-link" class="w-full">
                        Link Storage
                    </x-filament::button>
                </div>
            </x-filament::section>
        </div>

        <!-- Update & Deployment -->
        <x-filament::section>
            <x-slot name="heading">Update & Deployment</x-slot>
            <x-slot name="description">Tarik pembaruan kode terbaru dari GitHub.</x-slot>

            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <x-filament::button wire:click="updateApplication" size="lg" icon="heroicon-o-arrow-path"
                    class="w-full md:w-auto">
                    Tarik Update (Git Pull & Migrate)
                </x-filament::button>

                <x-filament::button wire:click="hardResetGit" color="danger" size="lg" icon="heroicon-o-fire"
                    wire:confirm="PERINGATAN: Ini akan menghapus semua perubahan lokal di server dan memaksa sinkronisasi dengan GitHub. Lanjutkan?"
                    class="w-full md:w-auto">
                    Hard Reset Git (Danger Zone)
                </x-filament::button>
            </div>
        </x-filament::section>

        <!-- Command Log Output -->
        @if($output)
            <x-filament::section>
                <x-slot name="heading">Hasil Proses Terakhir</x-slot>
                <div class="mt-4 p-4 bg-black rounded-xl border border-white/10 overflow-x-auto shadow-inner">
                    <pre class="text-code-green font-mono text-sm leading-relaxed"><code>{{ $output }}</code></pre>
                </div>
                <div class="mt-2 text-right">
                    <x-filament::button wire:click="$set('output', null)" color="gray" size="sm">Bersihkan
                        Log</x-filament::button>
                </div>
            </x-filament::section>
        @endif
    </div>

    <style>
        .text-code-green {
            color: #00FF41;
            text-shadow: 0 0 5px rgba(0, 255, 65, 0.5);
        }
    </style>
</x-filament-panels::page>