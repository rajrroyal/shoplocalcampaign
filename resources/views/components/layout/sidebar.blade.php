<div class="flex h-screen overflow-hidden bg-gray-100">
    <!-- Off-canvas menu for mobile -->
    <div class="md:hidden">
        <div
            x-show="nav"
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
                    <nav class="px-2 mt-5">
                        @php
                            $classes = [
                                'common' => 'group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md focus:outline-none transition ease-in-out duration-150',
                                'active' => 'text-gray-900 bg-gray-100 focus:bg-gray-200',
                                'inactive' => 'mt-1 text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:text-gray-900 focus:bg-gray-100'
                            ];

                            $iconClasses  = [
                                'common' => 'w-6 h-6 mr-4 transition duration-150 ease-in-out group-hover:text-gray-500',
                                'active' => 'text-gray-500 group-focus:text-gray-600',
                                'inactive' => 'text-gray-400 group-focus:text-gray-500'
                            ]
                        @endphp
                        <a href="{{ route('account-dashboard.index') }}" class="{{ $activeRoute == 'account-dashboard.index' ? $classes['active'] : $classes['inactive'] }} {{ $classes['common'] }}">
                            <x-icon.home class="{{ $activeRoute == 'account-connect.index'? $iconClasses['active'] : $iconClasses['inactive'] }} {{ $iconClasses['common'] }}"  />
                            Dashboard
                        </a>
                        <a href="{{ route('account-connect.index') }}" class="{{ $activeRoute == 'account-connect.index' ? $classes['active'] : $classes['inactive'] }} {{ $classes['common'] }}">
                            <x-icon.plus class="{{ $activeRoute == 'account-connect.index'? $iconClasses['active'] : $iconClasses['inactive'] }} {{ $iconClasses['common'] }}" />
                            Connect New Store
                        </a>
                    </nav>
                </div>
            </div>
            <div class="flex-shrink-0 w-14">
                <!-- Force sidebar to shrink to fit close icon -->
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64 bg-white border-r border-gray-200">
            <div class="flex flex-col flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <a class="block mx-auto w-36 h-36" href="{{ route('home') }}" aria-label="Site home">
                    <img src="{{ asset('images/default-site-logo.svg') }}" alt="Site logo" />
                </a>
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <nav class="flex-1 px-2 mt-5 bg-white">
                    @php
                        $classes = [
                            'common' => 'mb-1 flex items-center px-2 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out rounded-md group hover:text-gray-900 focus:outline-none',
                            'active' => 'text-gray-900 bg-gray-100 hover:bg-gray-100 focus:bg-gray-200',
                            'inactive' => 'text-gray-600 hover:bg-gray-50 focus:bg-gray-100'
                        ];

                        $iconClasses = [
                            'common' => 'w-6 h-6 mr-3 transition duration-150 ease-in-out group-hover:text-gray-500',
                            'active' => 'text-gray-500 group-focus:text-gray-600',
                            'inactive' => 'text-gray-400 group-focus:text-gray-500'
                        ];
                    @endphp
                    <a href="{{ route('account-dashboard.index') }}" class="{{ $activeRoute == 'account-dashboard.index' ? $classes['active'] : $classes['inactive'] }} {{ $classes['common'] }}">
                        <x-icon.home class="{{ $activeRoute == 'account-dashboard.index' ? $iconClasses['active'] : $iconClasses['inactive'] }} {{ $iconClasses['common'] }}" />
                        Dashboard
                    </a>
                    <a href="{{ route('account-connect.index') }}" class="{{ $activeRoute == 'account-connect.index' ? $classes['active'] : $classes['inactive'] }} {{ $classes['common'] }}">
                        <x-icon.plus class="{{ $activeRoute == 'account-connect.index' ? $iconClasses['active'] : $iconClasses['inactive'] }} {{ $iconClasses['common'] }}" />
                        Connect New Store
                    </a>
                </nav>
            </div>
        </div>
    </div>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <x-layout.navbar :hasSidebar="true" />
        
        {{ $slot }}
    </div>
</div>