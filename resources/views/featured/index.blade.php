@extends('layouts.default')

@section('content')
    <div class="relative px-4 pt-16 pb-20 bg-gray-50 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div class="absolute inset-0">
            <div class="bg-white h-1/3 sm:h-2/3"></div>
        </div>
        <div class="relative mx-auto max-w-7xl">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 sm:text-4xl sm:leading-10">
                    Featured Products
                </h2>
                <p class="max-w-2xl mx-auto mt-3 text-xl leading-7 text-gray-500 sm:mt-4">
                    Check out some featured items from local merchants
                </p>
            </div>
            <div class="grid max-w-lg gap-5 mx-auto mt-12 lg:grid-cols-3 lg:max-w-none">
                @foreach($products as $product)
                    <div class="flex flex-col overflow-hidden rounded-lg shadow-lg">
                        <div class="flex-shrink-0">
                            <a href="{{ $product->url }}" target="_blank" rel="noopener" aria-label="{{ $product->name }}">
                                <x-aspect-ratio ratio="1:1">
                                    @if($product->image)
                                        {{ $product->image->media->img('', ['class' => 'absolute w-full h-full object-contain', 'alt' => $product->store->name ]) }}
                                    @else
                                        <x-no-image />
                                    @endif
                                </x-aspect-ratio>
                            </a>
                        </div>
                        <div class="flex flex-col justify-between flex-1 p-6 bg-white">
                            <div class="flex-1">
                                <p class="text-sm font-medium leading-5 text-primary-600">
                                    @php
                                        $tags = explode(", ", $product->tags);
                                    @endphp
                                    @foreach($tags as $tag)
                                        <a href="#" class="hover:underline">{{ $tag }}</a>{{ !$loop->last ? ", " : null }}
                                    @endforeach
                                </p>
                                <a href="{{ $product->url }}" target="_blank" rel="noopener" class="block">
                                    <h3 class="mt-2 text-xl font-semibold leading-7 text-gray-900">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="mt-3 text-base leading-6 text-gray-500 truncate-3-lines">
                                        {{ strip_tags(htmlspecialchars_decode($product->description)) }}
                                    </p>
                                </a>
                            </div>
                            <a href="{{ $product->store->url }}" target="_blank" rel="noopener" aria-label="Product link" class="flex items-center mt-6">
                                <div class="flex-shrink-0">
                                    @if($product->store->image)
                                        <div class="w-10">
                                            <x-aspect-ratio>
                                                {{ $product->store->image->media->img('', ['class' => 'absolute w-full h-full object-contain', 'alt' => $product->store->name ]) }}
                                            </x-aspect-ratio>
                                        </div>
                                    @else
                                        <x-component :is="'icon.'.$product->store->source" class="w-10 h-10 text-gray-300" alt="{{ $product->store->name }}" />
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium leading-5 text-gray-900">
                                        {{ $product->store->name }}
                                    </p>
                                    <div class="flex text-sm leading-5 text-gray-500">
                                        <span>

                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ml-auto">
                                    <x-icon.arrow-right class="w-5 h-5 text-gray-400" />
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
