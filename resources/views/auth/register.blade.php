<x-guest-layout>

    <div class="flex-1 flex flex-col justify-start py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">

        <div class="mb-8">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Registration
            </h2>
            <p class="mt-2 text-sm text-gray-600 max-w">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Sign in
                </a>
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label for="first_name" value="{{ __('First name') }}" />
                <input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
            </div>

            <div  class="mt-4">
                <label for="last_name" value="{{ __('Surname') }}" />
                <input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" />
            </div>

            <div class="mt-4">
                <label for="email" value="{{ __('Email address') }}" />
                <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <label for="password" value="{{ __('Password') }}" />
                <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <label for="password_confirmation" value="{{ __('Retype password') }}" />
                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <div class="flex items-center">
                    <input id="agree_terms" name="agree_terms" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="agree_terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Terms of Service</a>
                        and
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <div class="flex items-center mt-4">
                <button class="flex justify-center py-2 px-3 border border-gray-700 rounded-md">
                    {{ __('Register') }}
                </button>
            </div>
        </form>

    </div>

</x-guest-layout>
