<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Maintenance Mode Card -->
            <x-filament::section icon="heroicon-o-shield-check" icon-color="primary">
                <x-slot name="heading">Mode Pemeliharaan</x-slot>
                <x-slot name="description">Kendalikan akses publik ke website.</x-slot>

                <div class="space-y-6 pt-4">
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/10">
                        <div class="space-y-1">
                            <p class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">
                                Status Sekarang</p>
                            @if($this->isMaintenanceMode())
                                <div class="flex items-center gap-2">
                                    <div class="h-2.5 w-2.5 rounded-full bg-rose-500 animate-pulse"></div>
                                    <span class="text-sm font-bold text-rose-600 dark:text-rose-400">AKTIF
                                        (MAINTENANCE)</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">AKTIF
                                        (PUBLIC)</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            @if($this->isMaintenanceMode())
                                <x-filament::button wire:click="toggleMaintenanceMode" color="success"
                                    icon="heroicon-m-check-badge">
                                    Matikan
                                </x-filament::button>
                            @else
                                <x-filament::button wire:click="toggleMaintenanceMode" color="danger"
                                    icon="heroicon-m-stop-circle">
                                    Nyalakan
                                </x-filament::button>
                            @endif
                        </div>
                    </div>

                    @if($this->isMaintenanceMode())
                        <div
                            class="relative overflow-hidden p-4 rounded-xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-950/20">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <x-filament::icon icon="heroicon-m-exclamation-triangle"
                                        class="h-5 w-5 text-amber-600 dark:text-amber-500" />
                                </div>
                                <div class="space-y-3">
                                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 leading-snug">
                                        Website sedang ditutup untuk umum. Gunakan link di bawah ini untuk melihat website
                                        sebagai admin:
                                    </p>
                                    <div class="group relative">
                                        <div
                                            class="flex items-center gap-2 p-2 bg-white dark:bg-black/20 rounded-lg border border-amber-200 dark:border-white/5 font-mono text-xs">
                                            <span
                                                class="text-primary break-all flex-1">{{ url('/' . $this->bypassSecret) }}</span>
                                            <a href="{{ url('/' . $this->bypassSecret) }}" target="_blank"
                                                class="p-1 hover:bg-gray-100 dark:hover:bg-white/5 rounded text-gray-400 hover:text-primary transition-colors">
                                                <x-filament::icon icon="heroicon-m-arrow-top-right-on-square"
                                                    class="h-4 w-4" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </x-filament::section>

            <!-- Optimization Card -->
            <x-filament::section icon="heroicon-o-bolt" icon-color="warning">
                <x-slot name="heading">Optimasi & Cache</x-slot>
                <x-slot name="description">Bersihkan dan optimasi sistem.</x-slot>

                <div class="grid grid-cols-1 gap-3 pt-4">
                    <div class="grid grid-cols-2 gap-3">
                        <x-filament::button wire:click="clearCache" color="warning" icon="heroicon-m-trash"
                            variant="outline" class="justify-start">
                            Hapus Cache
                        </x-filament::button>
                        <x-filament::button wire:click="optimize" color="info" icon="heroicon-m-bolt" variant="outline"
                            class="justify-start">
                            Optimasi
                        </x-filament::button>
                    </div>
                    <x-filament::button wire:click="linkStorage" color="gray" icon="heroicon-m-link" variant="outline"
                        class="w-full justify-start">
                        Perbarui Link Storage
                    </x-filament::button>
                </div>
            </x-filament::section>
        </div>

        <!-- Update & Deployment -->
        <x-filament::section icon="heroicon-o-arrow-path-rounded-square" icon-color="info">
            <x-slot name="heading">Update & Deployment</x-slot>
            <x-slot name="description">Sinkronisasi kode dari repositori GitHub.</x-slot>

            <div class="flex flex-wrap gap-4 pt-4">
                <x-filament::button wire:click="updateApplication" size="lg" icon="heroicon-m-cloud-arrow-down"
                    class="min-w-[200px]">
                    Tarik Update (Git Pull)
                </x-filament::button>

                <x-filament::button wire:click="hardResetGit" color="danger" size="lg" icon="heroicon-m-no-symbol"
                    variant="ghost"
                    wire:confirm="PERINGATAN KRITIS: Ini akan menghapus semua perubahan lokal di server dan memaksa sinkronisasi dengan GitHub. Lanjutkan?">
                    Hard Reset Git
                </x-filament::button>
            </div>
        </x-filament::section>

        <!-- Command Log Output -->
        @if($output)
            <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                <x-filament::section icon="heroicon-o-command-line">
                    <x-slot name="heading">Log Output</x-slot>
                    <x-slot name="headerEnd">
                        <x-filament::button wire:click="$set('output', null)" color="gray" size="xs" variant="ghost">
                            Bersihkan
                        </x-filament::button>
                    </x-slot>

                    <div
                        class="mt-2 p-4 bg-gray-950 rounded-xl border border-white/10 font-mono text-xs leading-relaxed overflow-x-auto shadow-inner">
                        <pre class="text-emerald-400 leading-relaxed"><code>{{ $output }}</code></pre>
                    </div>
                </x-filament::section>
            </div>
        @endif
    </div>
</x-filament-panels::page>