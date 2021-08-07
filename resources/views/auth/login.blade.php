<x-guest-layout>

    <div class="min-h-screen bg-white flex">

        <div class="flex-1 flex flex-col justify-start py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">

            <div>
                {{ config('package.name') }}
            </div>

            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="mb-8">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Sign in
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 max-w">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Register
                        </a>
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-red-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.authenticate') }}">
                    @csrf

                    <div>
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="email" name="email" autofocus />
                        @error('email') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="password" name="password" autocomplete="current-password" />
                        @error('password') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        <div class="text-sm">
                            @if (Route::has('password.forgot'))
                                <a class="font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('password.forgot') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center mt-4">
                        <button class="flex justify-center py-2 px-3 border border-gray-700 rounded-md">
                            {{ __('Sign In') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1 bg-gray-900">
            <div class="relative h-full flex justify-center z-10">
                <div class="p-40 w-full text-white">

                </div>
            </div>
        </div>

    </div>

</x-guest-layout>
