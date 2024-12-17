<aside :class="isMobile ? 'absolute z-30 bottom-0 top-0 left-0' : ''" x-show="!isMobile || sidenavOpen"
    @click.outside="sidenavOpen = false"
    class="w-60 max-h-screen justify-between bg-gray-800 text-gray-200 flex flex-col flex-nowrap">
    <div class="flex items-center justify-between px-6 py-8 border-b border-gray-500">
        <a class="text-white text-3xl font-bold uppercase text-center"> SIARDIKU</a>
        <button x-show="isMobile" @click="sidenavOpen = false" class="text-gray-400 hover:text-gray-200">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    <nav class="flex-1 flex flex-col justify-around overflow-y-auto px-2 space-y-2 mt-10">
        <div>
            <p class="text-gray-400 text-xs uppercase font-bold pl-4">Fitur Utama</p>
            <ul class="space-y-2 mt-2">
                <li>
                    <x-nav-link :href="route('dashboard')" :dashboard-active="request()->routeIs('dashboard')">
                        <i class="fas fa-home mr-3" aria-hidden="true"></i><span>{{__('Dashboard')}}</span></a>
                    </x-nav-link>
                </li>

                <li>
                    <x-nav-link :href="route('documents')" :dashboard-active="request()->routeIs('Split')">
                        <i class="fas fa-split mr-3" aria-hidden="true"></i><span>{{__('Split Dokumen ')}}</span></a>
                    </x-nav-link>
                </li>

                <li>
                    <x-nav-link :href="route('folders')" :dashboard-active="request()->routeIs('File')">
                        <i class="fa-solid fa-file mr-3" aria-hidden="true"></i><span>{{__('File Dokumen ')}}</span></a>
                    </x-nav-link>
                </li>
             

            </ul>
        </div>
        <div>
            <p class="text-gray-400 text-xs uppercase font-bold pl-4">User Info</p>
            <ul class="space-y-2 mt-2">

                <li>
                    <x-nav-link :href="route('profile.edit')" :dashboard-active="request()->routeIs('MyProfile')">
                        <i class="fas fa-user mr-3" aria-hidden="true"></i><span>{{__('My profile')}}</span></a>
                    </x-nav-link>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-nav-link :href="route('logout')" onclick="event.preventDefault();
                            this.closest('form').submit();">
                        <i class="fas fa-solid fa-arrow-right-from-bracket mr-3"></i>
                            {{ __('Log Out') }}
                        </x-nav-link>
                    </form>

                </li>
            </ul>
        </div>
        <div>
          <button @click="modalBantuan = true" @click.outside="modalBantuan = false" class="ml-2 rounded-md duration-100 ease-in-out p-2 flex space-x-4 items-center hover:bg-gray-600 transition-all w-full ">
            <i class="fas fa-sharp fa-square-question "></i>
            <span class="text-thin ">Bantuan</span>
          </button>
            <!-- <x-nav-link :href="route('help')" :dashboard-active="request()->routeIs('help')">
                <i class="fas fa-solid fa-square-question mr-3"
                    aria-hidden="true"></i><span>{{__('Bantuan ')}}</span>
            </x-nav-link> -->
            </li>
    </nav>
</aside>