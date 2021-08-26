<!-- Notificaton dropdown -->
<div class="flex-shrink-0 ml-4 sm:relative">
    <div @click="notifications = true">
        <button class="relative flex-shrink-0 p-1 text-gray-400 transition duration-150 ease-in-out border-2 border-transparent rounded-full focus-visible:border-gray-300 hover:text-gray-500 active:text-gray-500 focus:outline-none focus-visible:text-gray-500 focus-visible:bg-gray-100 active:bg-gray-100" aria-label="Notifications">
            <x-icon.bell class="w-6 h-6" />
            <span class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 font-sans text-xs text-white bg-red-400 rounded-full shadow-solid">1</span>
        </button>
    </div>

    <div
        x-show="notifications"
        x-cloak
        @click.away="notifications = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75""
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 w-screen max-w-sm mt-2 origin-top-right rounded-md shadow-lg"
    >
        <div class="py-1 bg-white rounded-md shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="notification-menu">
            <a href="#" class="flex items-start p-4 space-x-4 transition duration-150 ease-in-out border-b border-gray-200 hover:bg-gray-50">
                <x-icon.inbox class="flex-shrink-0 w-6 h-6 text-primary-600" />
                <div class="space-y-1">
                    <p class="text-base font-medium leading-6 text-gray-900">
                        Test Notification
                    </p>
                    <p class="text-sm leading-5 text-gray-500">
                        Something worthy of a notification.
                    </p>
                </div>
            </a>
            <a href="#" class="flex items-center justify-center p-3 space-x-3 text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out hover:bg-gray-100">
                <x-icon.bell class="flex-shrink-0 w-6 h-6 text-gray-400" />
                <span>View all notifications</span>
            </a>
        </div>
    </div>
</div>

<!-- Profile dropdown -->
<div class="relative flex-shrink-0 ml-4">
    <div @click="profile = true">
        <button class="flex text-sm transition duration-150 ease-in-out bg-gray-100 border-2 border-transparent rounded-full focus:outline-none focus-visible:border-gray-300 active:border-gray-300" aria-label="User" aria-haspopup="true">
            <img class="w-8 h-8 rounded-full" src="{{ asset('images/default-profile-image.svg') }}" alt="Profile Picture" />
        </button>
    </div>

    <div
        x-show="profile"
        x-cloak
        @click.away="profile = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 w-48 mt-2 origin-top-right rounded-md shadow-lg"
    >
        <div class="py-1 bg-white rounded-md shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
            <a href="{{ route('account-dashboard.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:bg-gray-100">Dashboard</a>
            <a @click="logout()" @click.prevent href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:bg-gray-100">Sign Out</a>
        </div>
    </div>
</div>