<nav class="relative z-10 bg-white shadow">
    <div class="px-2 mx-auto max-w-7xl sm:px-4 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center w-1/4 {{ $hasSidebar ? "md:hidden sm:w-auto" : "sm:hidden" }}">
                <!-- Mobile menu button -->
                <button @click="nav = !nav" class="inline-flex items-center justify-center p-1 text-gray-400 transition duration-150 ease-in-out rounded-full hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500" aria-label="Main menu" aria-expanded="false">
                    <span :class="{ 'hidden': nav, 'block': !nav }" class="block">
                        <x-icon.menu class="w-6 h-6" />
                    </span>
                    <!-- Icon when menu is open. -->
                    <span :class="{ 'block': nav, 'hidden': !nav }" class="hidden">
                        <x-icon.close class="w-6 h-6" />
                    </span>
                </button>
                <button @click="search = !search" class="flex-shrink-0 p-1 ml-4 text-gray-400 transition duration-150 ease-in-out border-2 border-transparent rounded-full sm:hidden focus-visible:border-gray-300 hover:text-gray-500 focus:outline-none focus-visible:text-gray-500 focus-visible:bg-gray-100" aria-label="Search">
                    <x-icon.search class="w-6 h-6" />
                </button>
            </div>
            @if($hasSidebar)
                <div class="flex items-center flex-shrink-0 md:hidden">
                    <a href="{{ route('home') }}" aria-label="Site home">
                        <img class="h-16" src="{{ asset('images/default-site-logo.svg') }}" alt="Site logo" />
                    </a>
                </div>
            @else
                <div class="flex items-center justify-center flex-1 sm:items-stretch sm:justify-start">
                    <div class="flex items-center flex-shrink-0">
                        <a href="{{ route('home') }}" aria-label="Site home">
                            <img class="h-16" src="{{ asset('images/default-site-logo.svg') }}" alt="Site logo" />
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex">
                        @php
                            $classes = [
                                'common' => 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out',
                                'active' => 'border-primary-500 text-gray-900 focus:border-primary-700',
                                'inactive' => 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300'
                            ];
                        @endphp
                        <a href="{{ route('home') }}" class="{{ $classes['common'] }} {{ $activeRoute == 'home' ? $classes['active'] : $classes['inactive'] }}">
                            Home
                        </a>
                        <a href="{{ route('featured.index') }}" class="ml-4 md:ml-8 {{ $classes['common'] }} {{ $activeRoute == 'featured.index' ? $classes['active'] : $classes['inactive'] }}">
                            Featured
                        </a>
                        <div x-data="{ categories: false}" :class="{'{{ $classes['active'] }}': categories, '{{ $classes['inactive'] }}': !categories}" class="ml-4 md:ml-8 {{ $classes['common'] }} {{ $activeRoute == 'featured.index' ? $classes['active'] : $classes['inactive'] }}">
                            <button @click="categories = !categories" type="button" class="inline-flex items-center self-stretch w-full focus:outline-none">
                                <span>Categories</span>
                                <span :class="{'rotate-0': !categories, 'rotate-180': categories}" class="ml-1 transform rotate-0">
                                    <x-icon.arrow-down class="w-5 h-5 transition duration-150 ease-in-out group-hover:text-gray-500 group-focus:text-gray-500" />
                                </span>
                            </button>
                            <x-layout.navbar.categories class="mt-1" />
                        </div>
                    </div>
                </div>
            @endif
            <div class="items-center justify-center flex-1 hidden px-2 sm:flex sm:ml-6 sm:justify-end">
                <div class="w-full max-w-lg lg:max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-icon.search class="w-5 h-5 text-gray-400" />
                        </div>
                        <input id="search" class="block w-full py-2 pl-10 pr-3 leading-5 placeholder-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md focus:outline-none focus-visible:placeholder-gray-400 focus-visible:border-blue-300 focus-visible:shadow-outline-blue sm:text-sm" placeholder="Search" type="search" />
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end w-1/4 sm:w-auto sm:ml-4">
                @auth
                    <x-layout.navbar.dropdown />
                @else
                    <div class="relative flex-shrink-0 text-gray-500 md:hidden">
                        <a href="{{ route('register.create') }}" class="rounded-md shadow-sm">
                            <x-button size="xs">
                                <span>Sign Up</span>
                            </x-button>
                        </a>
                    </div>
                    <div class="relative flex-shrink-0 hidden ml-4 md:block">
                        <a href="{{ route('register.create') }}" class="rounded-md shadow-sm">
                            <x-button>
                                <span>Sign Up</span>
                            </x-button>
                        </a>
                        <div class="inline-block mx-1 text-sm text-gray-500">or</div>
                        <a href="{{ route('login') }}" class="text-sm text-gray-700">
                            Sign In
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div 
        x-show="search"
        x-cloak
    >
        <div @click.away="search = false" class="flex items-center justify-center flex-1 px-2 py-4 sm:hidden sm:ml-6 sm:justify-end">
            <div class="w-full max-w-lg lg:max-w-xs">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <x-icon.search class="w-5 h-5 text-gray-400" />
                    </div>
                    <input id="search" class="block w-full py-2 pl-10 pr-3 leading-5 placeholder-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md focus:outline-none focus-visible:placeholder-gray-400 focus-visible:border-blue-300 focus-visible:shadow-outline-blue sm:text-sm" placeholder="Search" type="search" />
                </div>
            </div>
        </div>
    </div>

    @if(!$hasSidebar)
        <!-- Off-canvas menu for mobile -->
        <div class="md:hidden">
            <div x-show="nav" 
                x-cloak
                class="fixed inset-0 z-40 flex"
            >
                <div 
                    x-show="nav" 
                    x-transition:enter="transition-opacity ease-linear duration-300" 
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100" 
                    x-transition:leave="transition-opacity ease-linear duration-300" 
                    x-transition:leave-start="opacity-100" 
                    x-transition:leave-end="opacity-0" 
                    class="fixed inset-0"
                >
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div>

                <div 
                    x-show="nav" 
                    @click.away="nav = false" 
                    x-transition:enter="transition ease-in-out duration-300 transform" 
                    x-transition:enter-start="-translate-x-full" 
                    x-transition:enter-end="translate-x-0" 
                    x-transition:leave="transition ease-in-out duration-300 transform" 
                    x-transition:leave-start="translate-x-0" 
                    x-transition:leave-end="-translate-x-full" 
                    class="relative flex flex-col flex-1 w-full max-w-xs bg-white"
                >
                    <div class="absolute top-0 right-0 p-1 -mr-14">
                        <button @click="nav = false" class="flex items-center justify-center w-12 h-12 rounded-full focus:outline-none focus:bg-gray-600" aria-label="Close sidebar">
                            <x-icon.close class="w-6 h-6 text-white" />
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex items-center justify-center flex-shrink-0 px-4">
                            <a class="block w-36 h-36" href="{{ route('home') }}">
                                <img src="{{ asset('images/default-site-logo.svg') }}" />
                            </a>
                        </div>
                        <nav class="relative px-2 mt-5">
                            @php
                                $classes = [
                                    'common' => 'group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md focus:outline-none transition ease-in-out duration-150',
                                    'active' => 'text-gray-900 bg-gray-100 focus:bg-gray-200',
                                    'inactive' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:text-gray-900 focus:bg-gray-100'
                                ];
                            @endphp
                            <a href="{{ route('home') }}" class="mt-1 {{ $classes['common'] }} {{ $activeRoute == 'home' ? $classes['active'] : $classes['inactive'] }}"">
                                <x-icon.home class="w-6 h-6 mr-4 transition duration-150 ease-in-out group-hover:text-gray-500" />
                                <span>Home</span>
                            </a>
                            <a href="{{ route('featured.index') }}" class="mt-1 {{ $classes['common'] }} {{ $activeRoute == 'featured.index' ? $classes['active'] : $classes['inactive'] }}"">
                                <x-icon.home class="w-6 h-6 mr-4 transition duration-150 ease-in-out group-hover:text-gray-500" />
                                <span>Featured</span>
                            </a>
                            <div x-data="{ categories: false}" class="w-full">
                                <button @click="categories = !categories" type="button" :class="{'{{ $classes['active'] }}': categories, '{{ $classes['inactive'] }}': !categories}" class="w-full {{ $classes['common'] }} {{ $activeRoute == 'categories.index' ? $classes['active'] : $classes['inactive'] }}">
                                    <x-icon.cart class="w-6 h-6 mr-4 transition duration-150 ease-in-out group-hover:text-gray-500" />
                                    <span>Categories</span>
                                    <span :class="{'rotate-0': !categories, 'rotate-180': categories}" class="ml-auto mr-1 transform rotate-0">
                                        <x-icon.arrow-down class="w-5 h-5 transition duration-150 ease-in-out group-hover:text-gray-500 group-focus:text-gray-500" />
                                    </span>
                                </button>
                                <x-layout.navbar.categories />
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="flex-shrink-0 w-14">
                    <!-- Force sidebar to shrink to fit close icon -->
                </div>
            </div>
        </div>
    @endif
</nav>
