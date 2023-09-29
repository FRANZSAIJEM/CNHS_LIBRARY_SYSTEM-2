@if(session('success'))
<div class="success-message-container">
    <div class="success-message bg-green-100  text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
    <div class="loadingBar"></div>
</div>
@endif

<section>

    <header>
        <h2 class="text-lg font-medium">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form enctype="multipart/form-data"
        method="post"
        action="{{ route('profile.update') }}"
        class="mt-6 space-y-6"
    >
        @csrf
        @method('patch')


        @if ($user->image)
            <img class="rounded-full" src="{{($user->image) }}" id="previewImage" src="#" style="height: 250px; width: 255px;">
        @else
            <img id="previewImage" src="#" style="height: 350px; width: 255px;">
        @endif

        <div class="space-y-2">
            <x-form.label
                for="id_number"
                :value="__('ID Number')"
            />

            <x-form.input
                id="id_number"
                name="id_number"
                type="text"
                class="block w-full"
                :value="old('name', $user->id_number)"
                disabled
                autofocus
                autocomplete="id_number"
            />

            <x-form.error :messages="$errors->get('id_number')" />
        </div>

        <div class="space-y-2">
            <x-form.label
                for="name"
                :value="__('Name')"
            />

            <x-form.input
                id="name"
                name="name"
                type="text"
                class="block w-full"
                :value="old('name', $user->name)"

                autofocus
                autocomplete="name"
            />

            <x-form.error :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-form.label
                for="contact"
                :value="__('Contact')"
            />

            <x-form.input
                id="contact"
                name="contact"
                type="text"
                class="block w-full"
                :value="old('contact', $user->contact)"

                autofocus
                autocomplete="contact"
            />

            <x-form.error :messages="$errors->get('name')" />
        </div>



        <div class="space-y-2">
            <x-form.label for="gender" :value="__('Gender')" />

            <x-form.select
                id="gender"
                name="gender"
                class="block w-full"
                autofocus
                autocomplete="gender"
            >
                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
            </x-form.select>
        </div>


        <div class="space-y-2">
            <x-form.label
                for="grade_level"
                :value="__('Grade Level')"
            />

            <x-form.input
                id="grade_level"
                name="grade_level"
                type="text"
                class="block w-full"
                :value="old('grade_level', $user->grade_level)"

                autofocus
                autocomplete="grade_level"
            />

            <x-form.error :messages="$errors->get('name')" />
        </div>


        <div class="space-y-2">
            <x-form.label
                for="email"
                :value="__('Email')"
            />

            <x-form.input
                id="email"
                name="email"
                type="email"
                class="block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="email"
            />

            <x-form.error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500  dark:text-gray-400 dark:hover:text-gray-200 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-button>
                {{ __('Save') }}
            </x-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
<style>
      .success-message-container {
        position: relative;
    }

    .success-message {
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.3s, transform 0.3s;
    }
    .loadingBar{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background-color: #00af2cab;
        transition: width 3s linear;
    }

</style>


<script>
        window.addEventListener('DOMContentLoaded', (event) => {
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '1';
                successMessage.style.transform = 'translateY(0)';
            }, 100);
        }
    });

    window.addEventListener('DOMContentLoaded', (event) => {
        const successMessageContainer = document.querySelector('.success-message-container');
        const successMessage = document.querySelector('.success-message');
        const loadingBar = document.querySelector('.loadingBar');

        if (successMessage) {
            setTimeout(() => {
                loadingBar.style.width = '100%';
            }, 100);

            setTimeout(() => {
                loadingBar.style.opacity = '0';
                successMessage.style.opacity = '0';
                successMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    successMessageContainer.remove();
                }, 300);
            }, 3000); // 3 seconds for the loading bar to animate, then 100 milliseconds for the success message to disappear
        }
    });
</script>
