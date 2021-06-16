<x-guest-layout>

    <div class="min-h-screen bg-white flex">

        <div class="flex-1 flex flex-col justify-start py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">

            <div>
                Marketplace SDK
            </div>

            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="mb-8">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Two Factor Authentication
                    </h2>
                </div>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Please find below details which you should keep safe!') }}
                </div>

                @if(session()->get('auth')['svg_qr_code'])
                    <div class="flex py-3">
                        <div class="flex-shrink text-gray-900 rounded-md">
                            {!! session()->get('auth')['svg_qr_code'] !!}
                        </div>
                    </div>
                @endif

                @if(session()->get('auth')['recoveryCodes'])
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Recovery Codes
                    </h2>
                    <p class="mb-3">Keep in a safe place!</p>

                    <ul>
                        @foreach(session()->get('auth')['recoveryCodes'] as $code)
                            <li class="italics">{{ $code }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="flex items-center mt-8">
                    <a href="{{ route('login') }}" class="flex justify-center py-2 px-3 border border-gray-700 rounded-md">
                        {{ __('Log In') }}
                    </a>
                </div>

            </div>

        </div>

        <div class="hidden lg:block relative w-0 flex-1 bg-gray-900">
            <div class="relative h-full z-10">

            </div>
        </div>

    </div>

</x-guest-layout>
