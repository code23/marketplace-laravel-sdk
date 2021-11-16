<x-guest-layout>

    <div class="min-h-screen bg-white flex">

        <div class="flex-1 flex flex-col justify-start py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">

            <div>
                {{ config('package.name') }}
            </div>

            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="mb-8">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Forgotten password
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 max-w">
                        Remember password?
                        <a href="{{ route('mls.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Sign in
                        </a>
                    </p>
                </div>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('mls.password.email') }}">
                    @csrf

                    <div class="block">
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="email" name="email" :value="old('email')" required autofocus />
                        @error('email') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center mt-4">
                        <button class="flex justify-center py-2 px-3 border border-gray-700 rounded-md">
                            {{ __('Email Password Reset Link') }}
                        <button>
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
