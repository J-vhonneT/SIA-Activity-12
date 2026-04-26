@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manage Reservations') }}
                </h2>
            </div>

            <div class="p-6">
                @if($reservations->count())
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">User</th>
                                <th class="px-4 py-2 text-left">Date</th>
                                <th class="px-4 py-2 text-left">Time Range</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $reservation->id }}</td>
                                    <td class="px-4 py-2">{{ $reservation->user->name }}</td>
                                    <td class="px-4 py-2">{{ $reservation->date ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $reservation->time_start ?? 'N/A' }} - {{ $reservation->time_end ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($reservation->status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('reservations.edit', $reservation) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No reservations found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
