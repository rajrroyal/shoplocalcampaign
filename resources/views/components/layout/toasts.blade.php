<div 
    x-data="toasts()"
    x-init="init($el)"
    class="fixed top-0 right-0 z-30 w-11/12 max-w-sm mt-20 mr-2 pointer-events-none sm:mr-4 lg:mr-8"
>
    <template x-for="toast in toasts">
        <div
            :data-toast-id="toast.id"
            x-show="toast.open"
            @mouseenter="toast.timer.pause()"
            @mouseleave="toast.timer.resume()"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="w-full mb-2 bg-white rounded-lg shadow-lg pointer-events-auto"
        >
            <div class="overflow-hidden rounded-lg shadow-xs">
                <div class="relative p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <x-icon.check-circle x-show="toast.icon == 'success'" class="w-6 h-6 text-green-400" />
                            <x-icon.exclamation-circle x-show="toast.icon == 'warn'" class="w-6 h-6 text-yellow-400" />
                            <x-icon.x-circle x-show="toast.icon == 'error'" class="w-6 h-6 text-red-400" />
                            <x-icon.information-circle x-show="toast.icon == 'info'" class="w-6 h-6 text-blue-400" />
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p x-text="toast.title" class="text-sm font-medium leading-5 text-gray-900"></p>
                            <p x-text="toast.message" :class="{'mt-1': toast.title}" class="text-sm leading-5 text-gray-500"></p>
                        </div>
                        <div x-show="toast.closeButton" class="relative z-20 flex flex-shrink-0 ml-4">
                            <button @click="toast.open = false" class="inline-flex text-gray-400 transition duration-150 ease-in-out focus:outline-none focus:text-gray-500">
                                <x-icon.close class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                    
                    <a x-show="toast.url" :href="toast.url" class="absolute inset-0 z-10"></a>
                </div>
            </div>
        </div>
    </template>
</div>