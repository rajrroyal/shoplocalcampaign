@extends('layouts.account')

{{-- @push('scripts')
    <script>
        var products = @json($products);
    </script>
@endpush --}}

@section('content')
    <x-heading>
        {{ $store->name }} ({{ $store->typeDescription() }})
    </x-heading>
    <div class="flex items-center text-sm leading-5 text-gray-600 sm:mr-6">
        <x-icon.location class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
        <span>
            @php
                $address = join(", ", array_filter([
                    $store->address1,
                    $store->address2,
                    $store->city,
                    $store->province
                ]));
            @endphp
            {{ $address }}
        </span>
    </div>
    <div class="mx-auto max-w-7xl">
        <x-container>
            <ul>
                @foreach($products as $product)
                    <li>
                        <a href="#" class="block transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:bg-gray-50">
                            <div class="flex items-center px-4 py-4 sm:px-6">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="flex-shrink-0 w-14">
                                        <x-aspect-ratio>
                                            @if($product->image)
                                                {{ $product->image->media->img('', ['class' => 'absolute flex items-center justify-center object-contain w-full h-full', 'alt' => $product->name]) }}   
                                            @else
                                                <x-no-image />
                                            @endif
                                        </x-aspect-ratio>
                                    </div>
                                    <div class="flex-1 min-w-0 px-4 md:grid md:grid-cols-2 md:gap-4">
                                        <div>
                                            <div class="text-sm font-medium leading-5 truncate text-primary-600">
                                                {{ $product->name }}
                                            </div>
                                            <div class="flex items-center mt-2 text-sm leading-5 text-gray-500">
                                                <span class="truncate">
                                                    {{ strip_tags($product->description) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="hidden md:block">
                                            <div>
                                                <div class="text-sm leading-5 text-gray-900">
                                                    {{ $product->tags }}
                                                </div>
                                                <div class="flex items-center mt-2 text-sm leading-5 text-gray-500">
                                                    Updated&nbsp;
                                                    <time datetime="{{ $product->updated_at->format('Y-m-D') }}" x-text="dayjs('{{ $product->updated_at }}').fromNow()"></time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <x-icon.arrow-right class="w-5 h-5 text-gray-400" />
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>

            {{ $products->links() }}
        </x-container>
    </div>
@endsection
