<x-app-layout>
    @if(auth()->user()->hasRole(['admin', 'staff']))
        {{-- Admin View --}}
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Manage User QR Codes</h2>

                        <form method="GET" action="{{ route('qr-code.index') }}" class="mb-6">
                            <div class="flex">
                                <input type="text" name="search" class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" placeholder="Search by name, email, or slot..." value="{{ request('search') }}">
                                <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Search
                                </button>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slot</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($reservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->slot_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->reservation_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->reservation_time }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($reservation->qrCode && $reservation->qrCode->status == 'active') 
                                                        bg-green-100 text-green-800 
                                                    @else 
                                                        bg-red-100 text-red-800 
                                                    @endif">
                                                    {{ $reservation->qrCode ? ucfirst($reservation->qrCode->status) : 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('qr-code.index', ['reservation_id' => $reservation->id]) }}" class="text-indigo-600 hover:text-indigo-900">View QR</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                No reservations found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $reservations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- User View --}}
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Your QR Code</h2>
                        <p class="text-gray-500 mb-6">Scan this code at the cabinet to unlock it.</p>

                        <div class="bg-white p-4 rounded-lg inline-block mb-4 border">
                            @if($latestReservation->qrCode)
                                {!! $latestReservation->qrCode->qr_code_data !!}
                            @else
                                <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                    QR Code not found for this reservation.
                                </div>
                            @endif
                        </div>

                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-500">Reservation ID</p>
                            <p class="text-2xl font-bold text-gray-800 tracking-wider">{{ $latestReservation->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>