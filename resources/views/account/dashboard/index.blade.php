@extends('layouts.account')

@section('content')
    <x-heading>
        Dashboard
    </x-heading>
    @if(!sizeof($stores))
        <x-container>
            <div class="max-w-screen-xl px-4 py-12 mx-auto text-center sm:px-6 lg:py-16 lg:px-8">
                <h2 class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 sm:text-4xl sm:leading-10">
                    Ready to get started?
                </h2>
                <div class="flex justify-center mt-8">
                    <a href="{{ route('account-connect.index') }}">
                        <x-button size="lg">Connect Shop</x-button>
                    </a>
                </div>
            </div>
        </x-container>
    @else
        <x-container>
            <ul x-data='dashboardStores(@json($stores))' x-init="init()">
                <template x-for="store in stores">
                    <li>
                        <div :data-store-id="store.id" @progress="console.log($event)" class="block transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <a x-text="store.name" :href="store.details_url" class="text-sm font-medium leading-5 truncate text-primary-600"></a>
                                    <div class="flex flex-shrink-0 ml-2">
                                        <div x-show="store.updating">
                                            <x-button disabled="true" size="xs" class="flex">
                                                <x-icon.spinner class="w-4 h-4 mr-2 animation-spin animation-2s animation-linear" />Updating...
                                            </x-button>
                                        </div>
                                        <div x-show="!store.updating">
                                            <x-button @click="updateStore(store)" size="xs">
                                                Update Now
                                            </x-button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <div x-show="store.source == 'shopify'" class="flex items-center mr-6 text-sm leading-5 text-gray-500">
                                            <x-icon.shopify class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                                            Shopify
                                        </div>
                                        <div x-show="store.source == 'ecwid'" class="flex items-center mr-6 text-sm leading-5 text-gray-500">
                                            <x-icon.ecwid class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                                            PowerShop
                                        </div>
                                        <div x-show="store.source == 'shopcity'" class="flex items-center mr-6 text-sm leading-5 text-gray-500">
                                            <x-icon.shopcity class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                                            ShopCity
                                        </div>
                                        <div class="flex items-center mt-2 text-sm leading-5 text-gray-500 sm:mt-0">
                                            <x-icon.cart class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                                            <span x-text="`${store.num_products} Products`"></span>
                                        </div>
                                    </div>

                                    <div x-show="store.updating" class="flex items-center mt-2 text-sm leading-5 text-gray-500 sm:mt-0">
                                        <span data-progress-text class="mr-1.5">0%</span>
                                        <div class="relative flex w-full h-1 overflow-hidden bg-gray-300 rounded sm:w-48">
                                            <div data-progress-bar class="absolute inset-0 transition duration-300 ease-linear origin-left transform scale-x-0 bg-primary-500"></div>
                                        </div>
                                    </div>
                                    <div x-show="!store.updating" class="flex items-center mt-2 text-sm leading-5 text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        <span>
                                            Last updated
                                            <time :datetime="store.updated_at" x-text="dayjs(store.updated_at).fromNow()"></time>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </x-container>

        <div class="mt-6 text-center">
            {{ $stores->links() }}
        </div>
    @endif
@endsection
