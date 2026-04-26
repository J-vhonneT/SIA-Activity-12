<x-app-layout>
    @if(auth()->user()->hasRole(['admin', 'staff']))
        {{-- Admin View --}}
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">Reservations</h2>
                        
                                <x-action-button color="green" href="{{ route('manage-reservations.export-pdf') }}"  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Download PDF
                                </x-action-button>
                        </div>  

                        <form method="GET" action="{{ route('qr-code.index') }}" class="mb-6">
                            <div class="flex gap-2">
                                <input type="text" name="search" class="flex-1 border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-md shadow-sm px-3 py-2" placeholder="Search by name, email, or slot..." value="{{ request('search') }}">
                                <x-primary-button  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Search
                                </x-primary-button>
                                <x-secondary-button onclick="window.location.href='{{ route('manage-reservations') }}'">
                                    Clear
                                </x-secondary-button>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slot</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation ID</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($reservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->slot_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->reservation_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
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
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->id }}</td>
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
            <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-8 text-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Latest QR Code</h2>
                        <p class="text-gray-500 mb-6">This is the QR code for your most recent booking. Scan it at the cabinet to unlock.</p>
                        
                        @if($latestReservation && $latestReservation->qrCode)
                            <div class="bg-gray-50 p-6 rounded-xl inline-block border">
                                {!! $latestReservation->qrCode->qr_code_data !!}
                                <p class="mt-4 text-sm font-semibold text-gray-700 tracking-wider">
                                    Booking ID: <span class="font-bold text-gray-900">{{ $latestReservation->id }}</span>
                                </p>
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-200 p-6 rounded-xl text-center">
                                <h3 class="text-lg font-bold text-red-800">No Active QR Code</h3>
                                <p class="text-red-600 mt-2">You do not have any active reservations with a QR code. Please make a reservation first.</p>
                                <div class="mt-4">
                                    <x-action-button color="red" href="{{ route('reservations.create') }}">
                                        Make a Reservation
                                    </x-action-button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>