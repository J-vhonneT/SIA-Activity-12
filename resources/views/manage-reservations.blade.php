<x-app-layout>
    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

                <div class="p-8 text-gray-900">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">
                            Manage Reservations
                        </h1>
                    </div>

                    <!-- Search -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('manage-reservations') }}">
                            <div class="flex gap-2">

                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by ID, Name, Email..."
                                       class="flex-1 border-gray-300 rounded-md px-4 py-2 shadow-sm focus:ring focus:ring-blue-200">

                                <x-primary-button  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Search
                                </x-primary-button>

                                <x-secondary-button onclick="window.location.href='{{ route('manage-reservations') }}'">
                                    Clear
                                </x-secondary-button>

                            </div>
                        </form>
                    </div>

                    <!-- TABLE -->
                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Reservation ID</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Slot No.</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($reservations as $reservation)
                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 font-semibold">
                                            {{ $reservation->id }}
                                        </td>

                                        <td class="px-6 py-4 font-semibold">
                                            {{ $reservation->slot_number }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $reservation->user->name ?? 'N/A' }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $reservation->user->email ?? 'N/A' }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $reservation->reservation_date }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $reservation->start_time }} - {{ $reservation->end_time }}
                                        </td>

                                        <td class="px-6 py-4 text-center space-x-2">

                                            <x-action-button color="blue"
                                                href="{{ route('reservations.edit', $reservation) }}">
                                                Edit
                                            </x-action-button>

                                            <form method="POST"
                                                  action="{{ route('reservations.destroy', $reservation) }}"
                                                  class="inline-block"
                                                  onsubmit="return confirm('Delete this reservation?')">

                                                @csrf
                                                @method('DELETE')

                                                <x-action-button color="red" type="submit">
                                                    Delete
                                                </x-action-button>

                                            </form>

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                            No reservations found
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $reservations->links() }}
                    </div>

                </div>

            </div>

        </div>

    </div>
</x-app-layout>