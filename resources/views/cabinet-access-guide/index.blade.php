<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cabinet Access Guide') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
              <div class="bg-gray-50 rounded-lg p-6 border border-gray-100 flex justify-center">
                <div class="w-fit text-center">
                  <h3 class="text-lg font-medium mb-4 text-gray-900 text-center"
                      style="text-align: center !important;">HOW TO USE?</h3>

                  <div class="flex justify-center">
                    <ol class="space-y-4 text-gray-600">
    <li class="flex">
        <span class="w-6">1.</span>
        <span>Go to your reserved storage cabinet</span>
    </li>
    <li class="flex">
        <span class="w-6">2.</span>
        <span>Scan the QR code on the cabinet scanner</span>
    </li>
    <li class="flex">
        <span class="w-6">3.</span>
        <span>Cabinet will automatically unlock</span>
    </li>
    <li class="flex">
        <span class="w-6">4.</span>
        <span>Store or retrieve your helmet safely</span>
    </li>
</ol>

                  </div>

                </div>
              </div>
            </div>
        </div>
    </div>
</x-app-layout>