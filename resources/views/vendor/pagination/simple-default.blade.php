@if ($paginator->hasPages())

    @php        
        $button = join(' ', [
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
            "duration-150",
            "w-full",
            "justify-center",
            "rounded-md",
            "px-4",
            "py-2",
            "text-gray-700",
            "hover:text-gray-500",
            "active:text-gray-700"
        ]);
    @endphp
    
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex justify-between flex-1">
            <div class="w-20">
                @if (!$paginator->onFirstPage())
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" class="{{ $button }}">
                        Prev
                    </a>
                @endif
            </div>

            <div class="w-20">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="{{ $button }}">
                        Next
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
