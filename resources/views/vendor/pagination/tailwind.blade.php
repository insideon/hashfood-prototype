@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-lg dark:text-gray-500 dark:bg-zinc-800 dark:border-zinc-800">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 leading-5 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition dark:bg-zinc-800 dark:border-zinc-800 dark:text-gray-300 dark:hover:bg-zinc-700">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 leading-5 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition dark:bg-zinc-800 dark:border-zinc-800 dark:text-gray-300 dark:hover:bg-zinc-700">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-lg dark:text-gray-500 dark:bg-zinc-800 dark:border-zinc-800">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-600 leading-5 dark:text-gray-400">
                    @if ($paginator->firstItem())
                        <span class="font-medium text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                        -
                        <span class="font-medium text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                        /
                        <span class="font-medium text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium cursor-default rounded-l-lg leading-5" style="background-color: rgb(24 24 27); border: 1px solid rgb(28 28 32); color: rgb(113 113 122);" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-l-lg leading-5 transition focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500" style="background-color: rgb(24 24 27); border: 1px solid rgb(28 28 32); color: rgb(161 161 170);" onmouseover="this.style.backgroundColor='rgb(39 39 42)'; this.style.color='rgb(209 213 219)';" onmouseout="this.style.backgroundColor='rgb(24 24 27)'; this.style.color='rgb(161 161 170)';" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium cursor-default leading-5" style="background-color: rgb(24 24 27); border: 1px solid rgb(28 28 32); color: rgb(161 161 170);">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium cursor-default leading-5" style="background-color: rgb(39 39 42); border: 1px solid rgb(39 39 42); color: rgb(255 255 255);">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 transition" style="background-color: rgb(24 24 27); border: 1px solid rgb(28 28 32); color: rgb(209 213 219);" onmouseover="this.style.backgroundColor='rgb(39 39 42)';" onmouseout="this.style.backgroundColor='rgb(24 24 27)';" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium rounded-r-lg leading-5 transition focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500" style="background-color: rgb(24 24 27); border: 1px solid rgb(28 28 32); color: rgb(161 161 170);" onmouseover="this.style.backgroundColor='rgb(39 39 42)'; this.style.color='rgb(209 213 219)';" onmouseout="this.style.backgroundColor='rgb(24 24 27)'; this.style.color='rgb(161 161 170)';" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium cursor-default rounded-r-lg leading-5" style="background-color: rgb(24 24 27); border: 1px solid rgb(28 28 32); color: rgb(113 113 122);" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
