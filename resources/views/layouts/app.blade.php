<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Custom Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/helmet-logo-bg.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- QR Code Modal --}}
        @if (session('new_qr_code') && session('new_reservation'))
            <div id="qrCodeModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all" style="transform: scale(0.95); opacity: 0; animation: fadeInScale 0.3s ease-out forwards;">
                    <div class="p-8 text-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Reservation Confirmed!</h2>
                        <p class="text-gray-500 mb-6">Your unique booking ID is below. Scan the QR code at the cabinet to unlock it.</p>
                        
                        <div class="bg-gray-50 p-6 rounded-xl inline-block border">
                            {!! session('new_qr_code') !!}
                            <p class="mt-4 text-sm font-semibold text-gray-700 tracking-wider">
                                Booking ID: <span class="font-bold text-gray-900">{{ session('new_reservation')->id }}</span>
                            </p>
                        </div>

                        <div class="mt-8">
                            <button id="closeModalButton" class="w-full inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 transition-transform transform hover:scale-105">
                                Done
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('qrCodeModal');
                    const closeButton = document.getElementById('closeModalButton');

                    if (modal && closeButton) {
                        // Show the modal
                        modal.style.display = 'flex';

                        // Close the modal on button click
                        closeButton.addEventListener('click', function() {
                            modal.style.display = 'none';
                        });
                    }
                });
            </script>

            {{-- Simple CSS animation --}}
            <style>
                @keyframes fadeInScale {
                    from {
                        transform: scale(0.95);
                        opacity: 0;
                    }
                    to {
                        transform: scale(1);
                        opacity: 1;
                    }
                }
            </style>
        @endif
    </body>
</html>