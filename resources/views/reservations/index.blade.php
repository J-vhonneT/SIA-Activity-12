<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservations') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @isset($reservations)
                    {{-- Admin View: List all reservations --}}
                    <div class="overflow-x-auto">
                        <h3 class="font-bold mb-4 text-center">Manage Reservations</h3><br>
                        <div class="flex justify-between items-center mb-6">
                            <form method="GET" action="{{ route('reservations.index') }}" class="flex items-center">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Search slot, date, or user..."
                                    value="{{ request('search') }}"
                                    class="border-gray-300 rounded-md shadow-sm mr-2"
                                >
                                <x-primary-button>Search</x-primary-button>
                                @if(request('search'))
                                    <a href="{{ route('reservations.index') }}" class="ml-4 text-sm text-gray-500 hover:text-gray-700">Clear Search</a>
                                @endif
                            </form>
                            <a href="{{ route('reservations.pdf') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Download PDF
                            </a>
                        </div>
                        <table class="w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Slot</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations as $res)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $res->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">Slot {{ $res->slot_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $res->reservation_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ \Carbon\Carbon::parse($res->reservation_time)->format('g:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('qr-code.index', ['reservation_id' => $res->id]) }}" class="text-green-600 hover:text-green-900">View QR</a>
                                            <a href="{{ route('reservations.edit', $res) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                                            <form action="{{ route('reservations.destroy', $res) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Cancel</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $reservations->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    {{-- User View: Create a reservation --}}
                    <style>
                        .grid-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; max-width: 600px; }
                        .slot-box { border: 2px solid #e2e8f0; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: 0.3s; }
                        .slot-box.booked { background-color: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
                        .slot-box input[type="radio"] { display: none; }
                        .slot-box:not(.booked):has(input:checked) { background-color: #4f46e5; color: white; border-color: #4f46e5; }
                    </style>
                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <h3 class="text-center text-lg font-semibold mb-4">Select an Available Slot for Today</h3>
                        <div class="grid-container mx-auto">
                            @foreach($slots as $slot)
                                <label class="slot-box {{ in_array($slot, $bookedSlots) ? 'booked' : '' }}">
                                    <input type="radio" name="slot_number" value="{{ $slot }}" {{ in_array($slot, $bookedSlots) ? 'disabled' : '' }} required>
                                    <span>Slot {{ $slot }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-center gap-4">
                            <input type="hidden" name="reservation_date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                            <input type="time" name="reservation_time" required class="border-gray-300 rounded-md shadow-sm">
                            <x-primary-button>Confirm Booking</x-primary-button>
                        </div>
                    </form>
                @endisset
            </div>
        </div>
    </div>
</x-app-layout>