<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Make a Reservation') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                            <label class="slot-box {{ in_array($slot, $bookedSlots ?? []) ? 'booked' : '' }}">
                                <input type="radio" name="slot_number" value="{{ $slot }}" {{ in_array($slot, $bookedSlots ?? []) ? 'disabled' : '' }} required>
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
            </div>
        </div>
    </div>
</x-app-layout>