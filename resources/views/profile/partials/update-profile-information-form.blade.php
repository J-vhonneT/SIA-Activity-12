<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="mb-4">
            @if ($user->profile_photo)
            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                class="rounded-full object-cover border border-gray-300 shadow-sm"
                style="width: 82px; height: 82px;">
            @else
                <div style="
                width:82px;
                height:82px;
                border-radius:50%;
                background:#4f46e5;
                color:white;
                display:flex;
                align-items:center;
                justify-content:center;
                font-weight:bold;
                font-size:32px; /* scales with avatar */
                border:1px solid #d1d5db;
                box-shadow:0 1px 2px rgba(0,0,0,0.1);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="profile_photo" :value="__('Upload Profile Photo')" />
            <input id="profile_photo" type="file" name="profile_photo" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>

    </form>
</section>