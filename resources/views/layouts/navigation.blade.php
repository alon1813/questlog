<nav class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-black text-white">
                        QuestLog
                    </a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('user-list.index')" :active="request()->routeIs('user-list.index')">
                        Mi Colección
                    </x-nav-link>
                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.index')">
                        Noticias
                    </x-nav-link>
                    <x-nav-link :href="route('search.index')" :active="request()->routeIs('search.index')">
                        Buscar
                    </x-nav-link>
                    <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.index')">
                        Tienda
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div id="react-notifications"></div>

                <div class="mx-4">
                    <button @click="darkMode = !darkMode" class="p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg x-show="!darkMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.106a.75.75 0 010 1.06l-1.591 1.59a.75.75 0 01-1.06-1.061l1.59-1.59a.75.75 0 011.06 0zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.894 17.894a.75.75 0 011.06 0l1.59 1.59a.75.75 0 01-1.06 1.06l-1.59-1.59a.75.75 0 010-1.06zM12 18a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM5.106 17.894a.75.75 0 010-1.06l1.59-1.59a.75.75 0 111.06 1.06l-1.59 1.59a.75.75 0 01-1.06 0zM3 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H3.75A.75.75 0 013 12zM6.106 5.106a.75.75 0 011.06 0l1.59 1.59a.75.75 0 01-1.06 1.06L5.106 6.167a.75.75 0 010-1.06z" />
                        </svg>

                        <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <img class="h-8 w-8 rounded-full object-cover mr-2" 
                        src="{{ Auth::user()->avatar_path ? asset('storage/' . Auth::user()->avatar_path) : asset('images/default-avatar.png') }}" 
                        alt="Avatar">    
                        <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>

                        </button>

                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('wishlist.index')">
                            Carrito
                        </x-dropdown-link>

                        @can('manage-posts')

                            <x-dropdown-link :href="route('posts.admin.index')">Gestionar Noticias</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.comments.index')">Moderar Comentarios</x-dropdown-link> {{-- <-- AÑADE ESTE --}}
                            <x-dropdown-link :href="route('admin.users.index')">
                                Gestión de Usuarios
                            </x-dropdown-link>

                        @endcan

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"

                                    onclick="event.preventDefault();

                                                this.closest('form').submit();">

                                {{ __('Log Out') }}

                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">

                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">

                {{ __('Dashboard') }}

            </x-responsive-nav-link>

        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">

            <div class="px-4">

                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>

                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>

            </div>

            <div class="mt-3 space-y-1">

                <x-responsive-nav-link :href="route('profile.edit')">

                    {{ __('Profile') }}

                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <x-responsive-nav-link :href="route('logout')"

                            onclick="event.preventDefault();

                            this.closest('form').submit();">

                        {{ __('Log Out') }}

                    </x-responsive-nav-link>

                </form>
            </div>
        </div>
    </div>
</nav>

