<x-app-layout>
    <div class="py-12">

        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

                <div class="p-8 text-gray-900">

                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            Update Reservation
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">
                            Update reservation details below
                        </p>
                    </div>

                    <!-- FORM -->
                    <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">

                            <!-- Slot -->
                            <div>
                                <x-input-label for="slot_number" value="Slot Number" />
                                <select id="slot_number"
                                        name="slot_number"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring focus:ring-blue-200"
                                        required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('slot_number', $reservation->slot_number) == $i ? 'selected' : '' }}>
                                            Slot {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('slot_number')" class="mt-2" />
                            </div>

                            <!-- Time -->

                            {{-- Time Selection --}}
                            <div class="mt-4 flex justify-center gap-4 items-center">

                                <!-- Start Time -->
                                <div>
                                    <label class="font-medium">Start Time:</label>
                                    <select id="start_time" name="start_time" required class="border-gray-300 rounded-md shadow-sm">

                                        @for ($hour = 7; $hour <= 19; $hour++)
                                            @php $time = sprintf('%02d:00', $hour); @endphp

                                            <option value="{{ $time }}"
                                                {{ $reservation->start_time == $time ? 'selected' : '' }}>
                                                {{ date('g:i A', strtotime($time)) }}
                                            </option>
                                        @endfor

                                    </select>
                                </div>

                                <!-- End Time (display only) -->
                                <div>
                                    <label class="font-medium">End Time:</label>

                                    <select id="end_time_display" disabled class="border-gray-300 rounded-md shadow-sm"></select>

                                    <!-- THIS is what gets submitted -->
                                    <input type="hidden" name="end_time" id="end_time">
                                </div>

                            </div>

                            {{-- Availability Message --}}
                            <div class="text-center text-gray-500 my-4" id="availability-message">
                                Please select a start and end time to see availability.
                            </div>

                        </div>

                        <!-- ACTIONS -->
                        <div class="flex justify-end gap-3 mt-8">

                            <!-- Cancel -->
                            <x-secondary-button
                                onclick="window.location.href='{{ route('manage-reservations') }}'">
                                Cancel
                            </x-secondary-button>

                            <!-- Update -->
                            <x-primary-button>
                                Confirm
                            </x-primary-button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const start = document.getElementById('start_time');
        const endDisplay = document.getElementById('end_time_display');
        const endHidden = document.getElementById('end_time');

        function updateEnd() {
            let val = start.value;
            if (!val) return;

            let [h, m] = val.split(':').map(Number);
            let endH = h + 1;

            let end = String(endH).padStart(2, '0') + ':' + m;

            // display
            endDisplay.innerHTML = `<option>${new Date('1970-01-01T'+end+':00')
                .toLocaleTimeString([], {hour:'numeric', minute:'2-digit'})}</option>`;

            // hidden value
            endHidden.value = end;
        }

        updateEnd();
        start.addEventListener('change', updateEnd);
    });
    </script>
</x-app-layout>