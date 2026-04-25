<div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-white border-b border-gray-200">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-2 py-4 space-y-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>

        @if(in_array(Auth::user()->role, ['admin', 'staff']))
            <x-nav-link :href="route('cabinet-access-guide.index')" :active="request()->routeIs('cabinet-access-guide.index')">
                {{ __('Cabinet Access Guide') }}
            </x-nav-link>
            <x-nav-link :href="route('qr-code.index')" :active="request()->routeIs('qr-code.index')">
                {{ __('QR Code') }}
            </x-nav-link>
            <x-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.index')">
                {{ __('Manage Reservations') }}
            </x-nav-link>
        @else
            <x-nav-link :href="route('cabinet-access-guide.index')" :active="request()->routeIs('cabinet-access-guide.index')">
                {{ __('Cabinet Access Guide') }}
            </x-nav-link>
            <x-nav-link :href="route('reservations.create')" :active="request()->routeIs('reservations.create')">
                {{ __('Make a Reservation') }}
            </x-nav-link>
            <x-nav-link :href="route('qr-code.index')" :active="request()->routeIs('qr-code.index')">
                {{ __('QR Code') }}
            </x-nav-link>
        @endif
    </nav>

    <!-- User Profile Section -->
    <div class="px-2 py-4 border-t border-gray-200">
        <div class="flex items-center px-4">
            @if (Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;"
                    class="border border-gray-300">
            @else
                <x-avatar :name="Auth::user()->name" />
            @endif

            <div class="ms-3 min-w-0">
                <div class="font-medium text-base text-gray-800 truncate">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <!-- Authentication -->
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