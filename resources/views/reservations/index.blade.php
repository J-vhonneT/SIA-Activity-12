<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservations') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="overflow-x-auto">
                        <h3 class="font-bold mb-4 text-center">Manage Reservations</h3><br>
                        <div class="flex justify-between items-center mb-6">
                            <form method="GET" action="{{ route('reservations.index') }}" class="flex items-center gap-2">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Search slot, date, or user..."
                                    value="{{ request('search') }}"
                                    class="border-gray-300 rounded-md shadow-sm px-3 py-2"
                                >
                                <x-primary-button>Search</x-primary-button>
                                @if(request('search'))
                                    <x-secondary-button onclick="window.location.href='{{ route('reservations.index') }}'">Clear Search</x-secondary-button>
                                @endif
                            </form>
                            <x-action-button color="green" href="{{ route('reservations.pdf') }}">
                                📥 Download PDF
                            </x-action-button>
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
                                            <div class="flex items-center justify-center gap-2">
                                                <x-action-button color="green" href="{{ route('qr-code.index', ['reservation_id' => $res->id]) }}">
                                                    👁️ View QR
                                                </x-action-button>
                                                <x-action-button color="blue" href="{{ route('reservations.edit', $res) }}">
                                                    ✏️ Edit
                                                </x-action-button>
                                                <form action="{{ route('reservations.destroy', $res) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-action-button color="red" type="submit">
                                                        ❌ Cancel
                                                    </x-action-button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $reservations->appends(request()->query())->links() }}
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>