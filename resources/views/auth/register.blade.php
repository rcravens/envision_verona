<x-layouts.guest>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="my-4 font-xl text-center">
            {{__('Start Your 5-Day Free Trial')}}
        </div>

        <div>
            <x-form.label for="first_name" :value="__('First Name')"/>
            <x-form.input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name"/>
            <x-form.error :messages="$errors->get('first_name')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-form.label for="last_name" :value="__('Last Name')"/>
            <x-form.input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name"/>
            <x-form.error :messages="$errors->get('last_name')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-form.label for="email" :value="__('Email')"/>
            <x-form.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username"/>
            <x-form.error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-form.label for="password" :value="__('Password')"/>

            <x-form.input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password"/>

            <x-form.error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-form.label for="password_confirmation" :value="__('Confirm Password')"/>

            <x-form.input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password"/>

            <x-form.error :messages="$errors->get('password_confirmation')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-form.button-primary class="ms-4">
                {{ __('Register') }}
            </x-form.button-primary>
        </div>
    </form>
</x-layouts.guest>
