@if ($paginator->hasPages())

    @php
        $common = join(' ', [
            "relative",
            "inline-flex",
            "items-center",
            "py-2",
            "border",
            "border-gray-300",
            "bg-white",
            "text-sm",
            "leading-5",
            "font-medium",
            "focus:z-10",
            "focus:outline-none",
            "focus-visible:border-blue-300",
            "focus-visible:shadow-outline-blue",
            "active:bg-gray-100",
            "transition",
            "ease-in-out",
            "duration-150"
        ]);
        
        $button = join(' ', [
            $common,
            "w-full",
            "justify-center",
            "rounded-md",
            "px-4",
            "py-2",
            "text-gray-700",
            "hover:text-gray-500",
            "active:text-gray-700"
        ]);

        $active = join(' ', [
            "bg-gray-100"
        ]);

        $disabled = join(' ', [
            "cursor-default",
            "opacity-50"
        ]);

        $classes = [
            "onlyFirst" => join(' ', [
                $common,
                "px-2",
                "rounded-l-md",
                "text-gray-500",
                "hover:text-gray-400",
                "active:text-gray-500"
            ]),
            "onlyMiddle" => join(' ', [
                $common,
                "-ml-px",
                "px-4",
                "text-gray-700",
                "hover:text-gray-500",
                "active:text-gray-700"
            ]),
            "onlyLast" => join(' ', [
                $common,
                "-ml-px",
                "px-2",
                "rounded-r-md",
                "text-gray-500",
                "hover:text-gray-400",
                "active:text-gray-500"
            ])
        ]
    @endphp
    
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex justify-between flex-1 sm:hidden">
            <div class="w-20">
                @if (!$paginator->onFirstPage())
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" class="{{ $button }}">
                        Prev
                    </a>
                @endif
            </div>
            <div class="flex items-center justify-center flex-grow">
                <p class="text-sm leading-5 text-center text-gray-700">
                    Page
                    <span class="font-medium">{{ $paginator->currentPage() }}</span>
                    of
                    <span class="font-medium">{{ $paginator->lastPage() }}</span>
                </p>
            </div>
            <div class="w-20">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="{{ $button }}">
                        Next
                    </a>
                @endif
            </div>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm leading-5 text-gray-700">
                    Showing
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <ul class="relative z-0 inline-flex shadow-sm">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <li aria-disabled="true" aria-label="@lang('pagination.previous')">
                                <span class="{{ $classes['onlyFirst'] }} {{ $disabled }}" aria-hidden="true">
                                    <x-icon.arrow-left class="w-5 h-5" />
                                </span>
                            </li>
                        @else
                            <li>
                                <a class="{{ $classes['onlyFirst'] }}" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                                    <x-icon.arrow-left class="w-5 h-5" />
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li aria-disabled="true">
                                    <span class="{{ $classes['onlyMiddle'] }} cursor-default">{{ $element }}</span>
                                </li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <li aria-current="page">
                                        <a class="{{ $classes['onlyMiddle'] }} {{ $page == $paginator->currentPage() ? $active : null }}" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <li>
                                <a class="{{ $classes['onlyLast'] }}" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                                    <x-icon.arrow-right class="w-5 h-5" />
                                </a>
                            </li>
                        @else
                            <li aria-disabled="true" aria-label="@lang('pagination.next')">
                                <span class="{{ $classes['onlyLast'] }} {{ $disabled }}" aria-hidden="true">
                                    <x-icon.arrow-right class="w-5 h-5" />
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endif
