<div>
    <section class="relative overflow-hidden pt-44 pb-32 bg-background-light dark:bg-background-dark min-h-screen">
        <!-- Background Elements -->
        <div
            class="absolute top-0 right-0 w-1/3 h-1/3 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-1/4 h-1/4 bg-[#b8860b]/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-primary/10 text-primary text-sm font-bold tracking-widest uppercase mb-4 animate-fade-in">Layanan
                        Konsultasi</span>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-background-dark dark:text-white mb-6">Tanya Kiai
                        Baktijaya</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto">
                        Konsultasi masalah agama berbasis Aswaja An-Nahdliyah dengan bantuan kecerdasan buatan (KH.
                        Baktijaya AI) atau kirim pertanyaan langsung ke Kiai kami.
                    </p>
                </div>

                <!-- Toggle Navigation -->
                <div class="flex justify-center mb-8">
                    <div
                        class="inline-flex p-1 bg-gray-100 dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 shadow-inner">
                        <button wire:click="$set('isChatting', true)"
                            class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ $isChatting ? 'bg-white dark:bg-primary shadow-lg text-primary dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">robot_2</span>
                                Chat Bersama AI
                            </div>
                        </button>
                        <button wire:click="$set('isChatting', false)"
                            class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ !$isChatting ? 'bg-white dark:bg-primary shadow-lg text-primary dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">rate_review</span>
                                Kirim Pertanyaan
                            </div>
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-white/5 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                    @if($isChatting)
                        <!-- Chat Interface -->
                        <div class="flex flex-col h-[600px]">
                            <!-- Chat Box -->
                            <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-4 scroll-smooth">
                                @foreach($chatHistory as $chat)
                                    <div class="flex {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                                        <div
                                            class="max-w-[80%] flex items-start gap-3 {{ $chat['role'] === 'user' ? 'flex-row-reverse' : '' }}">
                                            <!-- Avatar -->
                                            <div
                                                class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $chat['role'] === 'user' ? 'bg-[#b8860b] text-white' : 'bg-primary text-white ring-4 ring-primary/20' }}">
                                                <span class="material-symbols-outlined text-xl italic font-bold">
                                                    {{ $chat['role'] === 'user' ? 'person' : 'school' }}
                                                </span>
                                            </div>

                                            <!-- Message -->
                                            <div
                                                class="p-4 rounded-2xl shadow-sm leading-relaxed text-sm md:text-base {{ $chat['role'] === 'user' ? 'bg-[#b8860b] text-white rounded-tr-none' : 'bg-gray-100 dark:bg-white/10 text-gray-800 dark:text-gray-200 rounded-tl-none' }}">
                                                {!! nl2br(e($chat['message'])) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div wire:loading wire:target="sendMessage" class="flex justify-start">
                                    <div class="max-w-[80%] flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center animate-pulse">
                                            <span class="material-symbols-outlined text-xl italic font-bold">school</span>
                                        </div>
                                        <div
                                            class="p-4 bg-gray-100 dark:bg-white/10 rounded-2xl rounded-tl-none flex gap-1">
                                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                            <div
                                                class="w-2 h-2 bg-gray-400 rounded-full animate-bounce [animation-delay:-0.15s]">
                                            </div>
                                            <div
                                                class="w-2 h-2 bg-gray-400 rounded-full animate-bounce [animation-delay:-0.3s]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Box -->
                            <div class="p-4 border-t border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                                <form wire:submit.prevent="sendMessage" class="flex gap-2">
                                    <input type="text" wire:model="userMessage" placeholder="Tanyakan sesuatu..."
                                        class="flex-1 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-6 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 text-gray-800 dark:text-white">
                                    <button type="submit"
                                        class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-all">
                                        <span class="material-symbols-outlined">send</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Modal Form -->
                        <div class="p-8 md:p-12">
                            <form wire:submit.prevent="submitForm" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nama
                                            Lengkap</label>
                                        <input type="text" wire:model="name"
                                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all">
                                        @error('name') <span
                                        class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Email
                                            (Opsional)</label>
                                        <input type="email" wire:model="email"
                                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all">
                                        @error('email') <span
                                        class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Tanyakan Ke
                                        Kiai</label>
                                    <select wire:model="kiai_id"
                                        class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all">
                                        <option value="">Pilih Kiai</option>
                                        @foreach($kiais as $kiai)
                                            <option value="{{ $kiai->id }}">{{ $kiai->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kiai_id') <span
                                    class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Kategori
                                        Pertanyaan</label>
                                    <select wire:model="category"
                                        class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all">
                                        <option value="ibadah">Ibadah</option>
                                        <option value="muamalah">Muamalah</option>
                                        <option value="keluarga">Keluarga</option>
                                        <option value="akhlak">Akhlak</option>
                                        <option value="aqidah">Aqidah</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Pertanyaan
                                        Anda</label>
                                    <textarea wire:model="question" rows="5"
                                        class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all"></textarea>
                                    @error('question') <span
                                    class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit"
                                    class="w-full py-4 rounded-2xl bg-primary text-white font-bold text-lg shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                                    <span wire:loading wire:target="submitForm"
                                        class="animate-spin h-5 w-5 border-2 border-white/20 border-t-white rounded-full"></span>
                                    <span wire:loading.remove wire:target="submitForm">Kirim Pertanyaan ke Kiai</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <!-- Public Questions List -->
                @if(count($publicQuestions) > 0)
                    <div style="margin-top: 100px !important;"
                        class="pt-16 border-t border-gray-100 dark:border-white/5 space-y-12 animate-fade-in">
                        <div class="text-center mb-12">
                            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-800 dark:text-white mb-4">Jawaban Kiai
                            </h2>
                            <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">Pertanyaan-pertanyaan yang
                                telah dijawab oleh para Kiai secara mendalam.</p>
                        </div>

                        <div class="grid gap-10">
                            @foreach($publicQuestions as $item)
                                <div
                                    class="bg-white dark:bg-white/5 rounded-[2.5rem] p-8 md:p-14 border border-gray-100 dark:border-white/10 shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                                    <div class="flex flex-wrap items-center justify-between gap-6 mb-12">
                                        <div class="flex items-center gap-12">
                                            <div
                                                class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center shadow-inner">
                                                <span class="material-symbols-outlined text-4xl">person</span>
                                            </div>
                                            <div>
                                                <div class="font-black text-2xl text-gray-800 dark:text-white">{{ $item->name }}
                                                </div>
                                                <div class="text-base font-medium text-gray-500 dark:text-gray-400 opacity-75">
                                                    {{ $item->created_at->format('d M Y') }} •
                                                    {{ $item->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="px-6 py-2.5 rounded-2xl bg-primary text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-primary/20">
                                            {{ $item->category }}
                                        </div>
                                    </div>

                                    <div class="mb-14">
                                        <h3
                                            class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-6 leading-tight italic">
                                            "{{ $item->question }}"
                                        </h3>
                                    </div>

                                    <div class="relative pl-6">
                                        <div class="flex items-center gap-6 mb-6">
                                            <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center shadow-lg ring-8 ring-primary/10">
                                                <span class="material-symbols-outlined text-xl">school</span>
                                            </div>
                                            <div class="text-2xl font-black text-primary tracking-tight">Jawaban dari
                                                {{ $item->kiai ? $item->kiai->name : 'Kiai' }}</div>
                                        </div>
                                        <div class="pl-16 border-l-4 border-primary/10 ml-6">
                                            <div class="text-gray-600 dark:text-gray-300 leading-relaxed prose dark:prose-invert max-w-none text-xl font-medium">
                                                {!! $item->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script shadow>
        document.addEventListener('livewire:initialized', () => {
            @this.on('form-submitted', () => {
                alert('Pertanyaan Anda telah dikirim. Kami akan meninjau dan segera merespon via website ini (halaman Tanya Kiai).');
            });
        });
    </script>
</div>