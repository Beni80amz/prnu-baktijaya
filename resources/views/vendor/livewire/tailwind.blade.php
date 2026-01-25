@php
    if (!isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
               (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
            JS
        : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation"
            class="flex flex-col md:flex-row items-center justify-between gap-4">

            {{-- Mobile View (Simple Prev/Next) --}}
            <div class="flex justify-between w-full md:hidden">
                @if ($paginator->onFirstPage())
                    <span
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-default rounded-lg">
                        Sebelumnya
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-primary transition">
                        Sebelumnya
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-primary transition">
                        Selanjutnya
                    </button>
                @else
                    <span
                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-default rounded-lg">
                        Selanjutnya
                    </span>
                @endif
            </div>

            {{-- Desktop View --}}
            <div class="hidden md:flex-1 md:flex md:items-center md:justify-between w-full">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan
                        <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                        dari
                        <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
                        hasil
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex shadow-sm rounded-lg">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="Sebelumnya">
                                <span
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-default rounded-l-lg dark:bg-white/5 dark:border-white/10 dark:text-white/20"
                                    aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:text-primary hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring ring-primary/30 active:bg-gray-100 transition ease-in-out duration-150 dark:bg-white/5 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/10 dark:hover:text-white"
                                aria-label="Sebelumnya">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span
                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default dark:bg-white/5 dark:border-white/10 dark:text-white/50">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span
                                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-primary border border-primary cursor-default dark:bg-primary dark:border-primary">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:text-primary hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring ring-primary/30 active:bg-gray-100 transition ease-in-out duration-150 dark:bg-white/5 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/10 dark:hover:text-white"
                                                aria-label="Go to page {{ $page }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:text-primary hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring ring-primary/30 active:bg-gray-100 transition ease-in-out duration-150 dark:bg-white/5 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/10 dark:hover:text-white"
                                aria-label="Selanjutnya">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="Selanjutnya">
                                <span
                                    class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-default rounded-r-lg dark:bg-white/5 dark:border-white/10 dark:text-white/20"
                                    aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>