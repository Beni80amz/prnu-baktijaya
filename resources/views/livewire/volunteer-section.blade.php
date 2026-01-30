<div class="mt-32">
    <div class="flex items-center gap-4 mb-10">
        <h3
            class="text-xl font-black text-primary dark:text-white uppercase tracking-widest bg-primary/10 px-6 py-2 rounded-xl">
            Relawan</h3>
        <div class="flex-1 h-px bg-primary/20 dark:bg-white/10"></div>
    </div>
    @if($volunteers->isEmpty())
        <div class="text-center py-8 text-gray-500">Belum ada data Relawan.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($volunteers as $v)
                <div
                    class="bg-white dark:bg-white/5 p-6 rounded-2xl border-l-4 border-l-primary border-primary/5 dark:border-white/10 shadow-sm hover:border-l-accent transition-colors flex items-center gap-4">
                    @if($v->photo)
                        <img src="{{ asset('storage/' . $v->photo) }}" alt="{{ $v->name }}"
                            class="size-16 rounded-full object-cover border-2 border-primary/10 dark:border-white/10 shrink-0">
                    @else
                        <div
                            class="size-16 rounded-full bg-primary/5 flex items-center justify-center border-2 border-primary/10 dark:border-white/5 shrink-0">
                            <span class="material-symbols-outlined text-3xl text-primary/20">person</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-500 dark:text-white/50 font-bold text-[10px] uppercase mb-1">
                            {{ $v->region->name ?? 'Relawan' }}
                        </p>
                        <h4 class="text-lg font-bold text-primary dark:text-white leading-tight">{{ $v->name }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $volunteers->links() }}
        </div>
    @endif
</div>