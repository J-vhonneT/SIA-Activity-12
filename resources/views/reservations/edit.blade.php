<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Reservation') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                    <div class="mt-2 text-2xl font-bold">
                        Update Your Reservation
                    </div>
                    <div class="mt-2 text-gray-600">
                        Need to make a change? Adjust the details below and save your reservation.
                    </div>
                </div>

                <div class="p-6 sm:px-10 bg-gray-50">
                    <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="slot_number" :value="__('Slot Number')" />
                                <select id="slot_number" name="slot_number" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('slot_number', $reservation->slot_number) == $i ? 'selected' : '' }}>
                                            Slot {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('slot_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="reservation_date" :value="__('Reservation Date')" />
                                <x-text-input id="reservation_date" name="reservation_date" type="date" class="mt-1 block w-full" :value="old('reservation_date', $reservation->reservation_date)" required />
                                <x-input-error :messages="$errors->get('reservation_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="reservation_time" :value="__('Reservation Time')" />
                                <x-text-input id="reservation_time" name="reservation_time" type="time" class="mt-1 block w-full" :value="old('reservation_time', $reservation->reservation_time)" required />
                                <x-input-error :messages="$errors->get('reservation_time')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                            <x-primary-button>
                                {{ __('Update Reservation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>