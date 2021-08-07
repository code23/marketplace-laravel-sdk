@if (Route::has('login'))
    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        @auth
            <a href="{{ route('welcome') }}" class="text-md text-gray-200 underline">Home</a>
            <a href="{{ route('user') }}" class="text-md text-gray-200 underline">User</a>
            <a href="{{ route('logout') }}" class="text-md text-gray-200 underline">Logout</a>
        @else
            <a href="{{ route('login') }}" class="text-md text-gray-200 underline">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-md text-gray-200 underline">Register</a>
            @endif
        @endauth
    </div>
@endif
