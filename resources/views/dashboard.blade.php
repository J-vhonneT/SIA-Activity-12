<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to Helmet Storage Reservation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (in_array($user->role, ['admin', 'staff']) && !empty($stats))
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                            System Statistics
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Total Users Card -->
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h4 class="font-semibold text-md text-gray-600">Total Users</h4>
                                    <p class="text-3xl font-bold">{{ $stats['totalUsers'] }}</p>
                                </div>
                            </div>
                            <!-- Active Reservations Card -->
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h4 class="font-semibold text-md text-gray-600">Active Reservations</h4>
                                    <p class="text-3xl font-bold">{{ $stats['activeReservations'] }}</p>
                                </div>
                            </div>
                            <!-- Available Cabinets Card -->
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h4 class="font-semibold text-md text-gray-600">Available Cabinets</h4>
                                    <p class="text-3xl font-bold">{{ $stats['availableCabinets'] }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{ __("You're logged in!") }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>