<x-guest-layout>

    <div class="min-h-screen bg-white flex">

        <div class="flex-1 flex flex-col justify-start py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">

            <div>
                {{ config('package.name') }}
            </div>

            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="mb-8">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Registration
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 max-w">
                        Already have an account?
                        <a href="{{ route('mls.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Sign in
                        </a>
                    </p>
                </div>

                <form method="POST" action="{{ route('mls.register') }}">
                    @csrf

                    <div>
                        <label for="first_name">{{ __('First name') }}</label>
                        <input id="first_name" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="text" name="first_name" :value="old('first_name')" autofocus autocomplete="first_name" />
                        @error('first_name') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div  class="mt-4">
                        <label for="last_name">{{ __('Surname') }}</label>
                        <input id="last_name" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="text" name="last_name" :value="old('last_name')" autocomplete="last_name" />
                        @error('last_name') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="email">{{ __('Email address') }}</label>
                        <input id="email" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="email" name="email" :value="old('email')" />
                        @error('email') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="email">{{ __('Marketplace Name') }}</label>
                        <input id="team_name" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="text" name="team_name" :value="old('team_name')" />
                        @error('team_name') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="password" name="password" autocomplete="new-password" />
                        @error('password') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="password_confirmation">{{ __('Confirm password') }}</label>
                        <input id="password_confirmation" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="password" name="password_confirmation" autocomplete="new-password" />
                        @error('password_confirmation') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
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
                        @error('agree_terms') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center mt-4">
                        <button class="flex justify-center py-2 px-3 border border-gray-700 rounded-md">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>

            </div>

        </div>

        <div class="hidden lg:block relative w-0 flex-1 bg-gray-900">
            <div class="relative h-full flex items-center justify-center z-10">
                <div class="w-1/2 text-white">
                    @if(!empty($user))
                        <pre>{{ print_r($user, true) }}</pre>
                    @endif
                </div>
            </div>
        </div>

    </div>

</x-guest-layout>
