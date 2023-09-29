<style scoped>
    .fields{
        display: flex;
    }

    @media (max-width: 1440px) and (max-height: 640px) {
        .fields{
            display: block;
        }
    }
</style>

<x-guest-layout>
    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div class="">


                <div class="grid gap-5">
                    <div class="fields">
                        <div>

                            <!-- Name -->
                            <div class="space-y-2 m-5">
                                <x-form.label
                                    for="name"
                                    :value="__('Name')"
                                />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-user aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input
                                        withicon
                                        id="name"
                                        class="block w-full"
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        required
                                        autofocus
                                        placeholder="{{ __('Name') }}"
                                    />
                                </x-form.input-with-icon-wrapper>
                            </div>




                            <div class="space-y-2 m-5">
                                <x-form.label
                                    for="id_number"
                                    :value="__('ID Number')"
                                />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-key aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input
                                        withicon
                                        id="id_number"
                                        class="block w-full"
                                        type="text"
                                        name="id_number"
                                        :value="old('id_number')"
                                        required
                                        autofocus
                                        placeholder="{{ __('ID Number') }}"
                                    />
                                </x-form.input-with-icon-wrapper>
                            </div>

                            <div class="space-y-2 m-5">
                                <x-form.label
                                    for="email"
                                    :value="__('Email Address')"
                                />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-mail aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input
                                        withicon
                                        id="email"
                                        class="block w-full"
                                        type="email"
                                        name="email"
                                        :value="old('email')"
                                        required
                                        autofocus
                                        placeholder="{{ __('Email Address') }}"
                                    />
                                </x-form.input-with-icon-wrapper>
                            </div>

                            <div class="space-y-2 m-5">
                                <x-form.label
                                    for="contact"
                                    :value="__('Contact Number')"
                                />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-phone aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input
                                        withicon
                                        id="contact"
                                        class="block w-full"
                                        type="text"
                                        name="contact"
                                        :value="old('contact')"
                                        autofocus
                                        placeholder="{{ __('Contact Number') }}"
                                    />
                                </x-form.input-with-icon-wrapper>
                            </div>

                            </div>
                            <div>


                                <div class="space-y-2 m-5">
                                    <x-form.label for="gender" :value="__('Gender')" />

                                    <x-form.select
                                        id="gender"
                                        name="gender"
                                        :value="old('gender')"
                                        class="block w-full"
                                        autofocus
                                    >
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </x-form.select>
                                </div>



                            <div class="space-y-2 m-5">
                                <x-form.label
                                    for="grade_level"
                                    :value="__('Grade Level')"
                                />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-menu aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input
                                        withicon
                                        id="grade_level"
                                        class="block w-full"
                                        type="text"
                                        name="grade_level"
                                        :value="old('grade_level')"
                                        autofocus
                                        placeholder="{{ __('Grade Level') }}"
                                    />
                                </x-form.input-with-icon-wrapper>
                            </div>



                            <!-- Password -->
                            <div class="space-y-2 m-5">
                                <x-form.label
                                    for="password"
                                    :value="__('Password')"
                                />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-lock-closed aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input
                                        withicon
                                        id="password"
                                        class="block w-full"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="new-password"
                                        placeholder="{{ __('Password') }}"
                                    />
                                </x-form.input-with-icon-wrapper>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2 m-5">
                                <x-form.label
                                    for="password_confirmation"
                                    :value="__('Confirm Password')"
                                />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-lock-closed aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input
                                        withicon
                                        id="password_confirmation"
                                        class="block w-full"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        placeholder="{{ __('Confirm Password') }}"
                                    />
                                </x-form.input-with-icon-wrapper>
                            </div>

                            </div>
                    </div>
                    <div>
                        <x-button class="justify-center w-full gap-2">
                            <x-heroicon-o-user-add class="w-6 h-6" aria-hidden="true" />

                            <span>{{ __('Register') }}</span>
                        </x-button>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Already registered?') }}
                        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                            {{ __('Login') }}
                        </a>
                    </p>
                </div>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
