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

                        <div class="mt-8">
                            <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                                Cabinet Status (Today)
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach($cabinets as $number => $cabinet)
                                    <div class="relative">
                                        <div class="
                                            @if($cabinet['status'] == 'available')
                                                bg-green-100 border-green-400
                                            @else
                                                bg-red-100 border-red-400
                                            @endif
                                            border rounded-lg p-4 flex flex-col items-center justify-center aspect-square">
                                            <span class="text-2xl font-bold text-gray-700">{{ $number }}</span>
                                            <span class="text-xs font-medium
                                                @if($cabinet['status'] == 'available')
                                                    text-green-700
                                                @else
                                                    text-red-700
                                                @endif
                                            ">{{ ucfirst($cabinet['status']) }}</span>
                                        </div>
                                        @if($cabinet['status'] == 'reserved')
                                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                                <div class="text-center text-white p-2">
                                                    <p class="text-sm font-semibold">{{ $cabinet['user_name'] }}</p>
                                                    <p class="text-xs">{{ $cabinet['time'] }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
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