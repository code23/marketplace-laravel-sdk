@if (Route::has('login'))
    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        @auth
            <a href="{{ route('mls.welcome') }}" class="text-md text-gray-200 underline">Home</a>
            <a href="{{ route('mls.user') }}" class="text-md text-gray-200 underline">User</a>
            <a href="{{ route('mls.logout') }}" class="text-md text-gray-200 underline">Logout</a>
        @else
            <a href="{{ route('mls.login') }}" class="text-md text-gray-200 underline">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('mls.register') }}" class="ml-4 text-md text-gray-200 underline">Register</a>
            @endif
        @endauth
    </div>
@endif
