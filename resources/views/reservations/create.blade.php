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
                    .slot-box.booked { background-color: #fecaca; color: #9ca3af; cursor: not-allowed; border-color: #f87171; }
                    .slot-box.disabled { background-color: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
                    .slot-box input[type="radio"] { display: none; }
                    .slot-box:not(.booked):not(.disabled):has(input:checked) { background-color: #4f46e5; color: white; border-color: #4f46e5; }
                </style>
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf
                    <h3 class="text-center text-lg font-semibold mb-4">Select a Time and Available Slot</h3>

                    {{-- Time Selection --}}
                    <div class="mt-4 flex justify-center gap-4 items-center">
                        <input type="hidden" name="reservation_date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                        
                        <div>
                            <label for="start_time" class="font-medium">Start Time:</label>
                            <select id="start_time" name="start_time" required class="border-gray-300 rounded-md shadow-sm">
                                <option value="" selected disabled>Select start</option>
                                @for ($hour = 7; $hour <= 19; $hour++)
                                    <option value="{{ sprintf('%02d', $hour) }}:00">{{ date('g:i A', strtotime(sprintf('%02d', $hour) . ':00')) }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="end_time" class="font-medium">End Time:</label>
                            <select id="end_time" name="end_time" required class="border-gray-300 rounded-md shadow-sm" disabled>
                                <option value="" selected disabled>Select end</option>
                            </select>
                        </div>
                    </div>

                    {{-- Availability Message --}}
                    <div class="text-center text-gray-500 my-4" id="availability-message">
                        Please select a start and end time to see availability.
                    </div>

                    {{-- Slots Grid --}}
                    <div id="slots-grid" class="grid-container mx-auto mt-8">
                        @foreach($slots as $slot)
                            <label class="slot-box disabled">
                                <input type="radio" name="slot_number" value="{{ $slot }}" disabled required>
                                <span>Slot {{ $slot }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-6 flex justify-center">
                        <x-primary-button>Confirm Booking</x-primary-button>
                    </div>
                </form>

                {{-- Today's Booked Slots Card --}}
                @if($todaysReservations->isNotEmpty())
                    <div class="mt-8 pt-6 border-t">
                        <h3 class="text-center text-lg font-semibold mb-4">Today's Booked Slots</h3>
                        <div class="max-w-md mx-auto bg-gray-50 p-4 rounded-lg shadow-inner">
                            <ul class="space-y-2">
                                @foreach($todaysReservations as $reservation)
                                    <li class="flex justify-between items-center text-sm">
                                        <span class="font-semibold text-gray-700">Slot {{ $reservation->slot_number }}</span>
                                        <span class="text-gray-500">
                                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startTimeSelect = document.getElementById('start_time');
        const endTimeSelect = document.getElementById('end_time');
        const slotsGrid = document.getElementById('slots-grid');
        const availabilityMessage = document.getElementById('availability-message');
        const date = document.querySelector('input[name="reservation_date"]').value;

        function resetSlots(message = 'Please select a start and end time to see availability.') {
            availabilityMessage.textContent = message;
            availabilityMessage.style.display = 'block';
            slotsGrid.querySelectorAll('.slot-box').forEach(label => {
                label.classList.remove('booked');
                label.classList.add('disabled');
                label.querySelector('input').disabled = true;
                label.querySelector('input').checked = false;
            });
        }

        function resetEndTimeSelect() {
            endTimeSelect.innerHTML = '<option value="" selected disabled>Select end</option>';
            endTimeSelect.disabled = true;
        }

        startTimeSelect.addEventListener('change', function () {
            const startTimeValue = this.value;
            resetEndTimeSelect();
            resetSlots();

            if (!startTimeValue) return;

            const startHour = parseInt(startTimeValue.split(':')[0]);

            // Populate end time options, up to 5 hours
            for (let i = 1; i <= 5; i++) {
                const endHour = startHour + i;
                if (endHour > 20) break; // Do not allow bookings past 8 PM

                const option = document.createElement('option');
                const time = `${String(endHour).padStart(2, '0')}:00`;
                option.value = time;
                option.textContent = new Date(`1970-01-01T${time}Z`).toLocaleTimeString('en-US', {hour: 'numeric', minute:'2-digit', hour12: true, timeZone: 'UTC'});
                endTimeSelect.appendChild(option);
            }
            endTimeSelect.disabled = false;
        });

        function fetchAvailability() {
            const startTime = startTimeSelect.value;
            const endTime = endTimeSelect.value;

            if (!startTime || !endTime) {
                resetSlots();
                return;
            }

            availabilityMessage.style.display = 'none';

            fetch(`/booked-slots?date=${date}&start_time=${startTime}&end_time=${endTime}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const bookedSlots = data.booked_slots;
                    slotsGrid.querySelectorAll('.slot-box').forEach(label => {
                        const slotInput = label.querySelector('input');
                        const slotNumber = parseInt(slotInput.value);

                        if (bookedSlots.includes(slotNumber)) {
                            label.classList.add('booked');
                            label.classList.remove('disabled');
                            slotInput.disabled = true;
                            slotInput.checked = false;
                        } else {
                            label.classList.remove('disabled', 'booked');
                            slotInput.disabled = false;
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching available slots:', error);
                    resetSlots('Could not fetch availability. Please try again.');
                });
        }

        endTimeSelect.addEventListener('change', fetchAvailability);
    });
</script>
</x-app-layout>