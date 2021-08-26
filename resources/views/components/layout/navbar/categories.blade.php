<div class="z-0">
    <div 
        x-show="categories"
        x-cloak
        @click.away="categories = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        {{ $attributes->merge(['class' => 'absolute inset-x-0 transform shadow-lg top-full']) }}
    >
        <div class="absolute inset-0 flex">
            <div class="w-1/2 bg-white"></div>
            <div class="w-1/2 bg-gray-50"></div>
        </div>
        <div class="relative grid grid-cols-1 mx-auto max-w-7xl lg:grid-cols-2">
            <nav class="grid px-4 py-8 bg-white sm:row-gap-10 sm:grid-cols-2 sm:col-gap-8 sm:py-12 sm:px-6 lg:px-8 xl:pr-12">
                <div class="space-y-5">
                    <h3 class="text-sm font-medium leading-5 tracking-wide text-gray-500 uppercase">
                        Top Categories
                    </h3>
                    <ul class="space-y-6">
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.clothing class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Clothing & Accessories</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.home class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Home & Garden</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.electronics class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Electronics</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.health class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Health & Wellness</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.bicycle class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Sports & Outdoors</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="space-y-5">
                    <h3 class="hidden text-sm font-medium leading-5 tracking-wide text-gray-500 uppercase sm:block">
                        &nbsp;
                    </h3>
                    <ul class="space-y-6">
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.clothing class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Clothing & Accessories</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.home class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Home & Garden</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.electronics class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Electronics</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.health class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Health & Wellness</span>
                            </a>
                        </li>
                        <li class="flow-root">
                            <a href="#" class="flex items-center p-3 -m-3 space-x-4 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                                <x-icon.bicycle class="flex-shrink-0 w-6 h-6 text-gray-400" />
                                <span>Sports & Outdoors</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="self-end mt-5 text-sm font-medium leading-5 text-right lg:mt-0 lg:col-span-2">
                    <a href="#" class="inline-flex items-center transition duration-150 ease-in-out text-primary-600 hover:text-primary-500">
                        View all categories <x-icon.arrow-right class="inline-block w-5 h-5 ml-1" />
                    </a>
                </div>
            </nav>
            <div class="px-4 py-8 bg-gray-50 sm:py-12 sm:px-6 lg:px-8 xl:pr-12">
                <div class="flex flex-col h-full space-y-5 ">
                    <h3 class="text-sm font-medium leading-5 tracking-wide text-gray-500 uppercase">
                        Featured
                    </h3>
                    <div class="flex items-center justify-center flex-1 border-4 border-gray-200 border-dashed rounded-lg">
                        <span class="p-4 text-gray-400">Featured items? Sub categories?</span>
                    </div>

                    <div class="self-end mt-5 text-sm font-medium leading-5 text-right lg:mt-0">
                        <a href="#" class="inline-flex items-center transition duration-150 ease-in-out text-primary-600 hover:text-primary-500">
                            View all featured <x-icon.arrow-right class="inline-block w-5 h-5 ml-1" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
