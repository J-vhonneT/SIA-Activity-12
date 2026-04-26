@props(['color' => 'blue', 'href' => null])

@php
$colorClasses = match($color) {
    'blue' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'red' => 'bg-red-600 hover:bg-red-700 text-white',
    'green' => 'bg-green-600 hover:bg-green-700 text-white',
    'gray' => 'bg-gray-500 hover:bg-gray-600 text-white',
    default => 'bg-blue-600 hover:bg-blue-700 text-white',
};
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ' . $colorClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ' . $colorClasses]) }}>
        {{ $slot }}
    </button>
@endif
