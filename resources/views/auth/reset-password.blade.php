<x-guest-layout>

    <div class="min-h-screen bg-white flex">

        <div class="flex-1 flex flex-col justify-start py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">

            <div>
                Marketplace SDK
            </div>

            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="mb-8">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Reset password
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 max-w">
                        Remember password?
                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Sign in
                        </a>
                    </p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="block">
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="email" name="email" :value="old('email', $request->email)" autofocus />
                        @error('email') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="password" name="password" autocomplete="new-password" />
                        @error('password') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="password" name="password_confirmation" autocomplete="new-password" />
                        @error('password_confirmation') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center mt-4">
                        <button class="flex justify-center py-2 px-3 border border-gray-700 rounded-md">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>

            </div>

        </div>

        <div class="hidden lg:block relative w-0 flex-1 bg-gray-900">
            <div class="relative h-full flex items-center justify-center z-10">
                <div class="w-1/2 text-white">

                </div>
            </div>
        </div>

    </div>

</x-guest-layout>
