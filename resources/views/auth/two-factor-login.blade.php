<x-guest-layout>

    <div class="min-h-screen bg-white flex">

        <div class="flex-1 flex flex-col justify-start py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">

            <div>
                {{ config('package.name') }}
            </div>

            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="mb-8">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Two Factor Authentication
                    </h2>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-red-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('mls.two-factor.validation') }}">

                    @csrf

                    <input id="return_url"          type="hidden" name="return_url"             value="{{ $return_url }}" />
                    <input id="authentication_type" type="hidden" name="authentication_type"    value="code" />

                    <div>
                        <label for="authentication_code">{{ __('Authentication Code') }}</label>
                        <input id="authentication_code" class="block mt-1 p-1 px-2 w-full border border-gray-300 rounded" type="text" name="authentication_code" autofocus />
                        @error('authentication_code') <div class="text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center mt-4">
                        <button class="flex justify-center py-2 px-3 border border-gray-700 rounded-md">
                            {{ __('Verify') }}
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
