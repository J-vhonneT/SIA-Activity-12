<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Your QR Code</h2>
                    <p class="text-gray-500 mb-6">Scan this code at the cabinet to unlock it.</p>

                    <div class="bg-white p-4 rounded-lg inline-block mb-4 border">
                        <!-- Dynamic QR Code Image -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode('RESERVATION_ID:' . $reservation->id) }}" alt="QR Code">
                    </div>

                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-500">Reservation ID</p>
                        <p class="text-2xl font-bold text-gray-800 tracking-wider">{{ $reservation->id }}</p>
                    </div>

                    <div class="flex justify-center space-x-8 mt-6">
                        <button class="text-gray-600 hover:text-gray-800 flex flex-col items-center">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span class="text-xs">Download</span>
                        </button>
                        <button class="text-gray-600 hover:text-gray-800 flex flex-col items-center">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12s-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path></svg>
                            <span class="text-xs">Share</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>