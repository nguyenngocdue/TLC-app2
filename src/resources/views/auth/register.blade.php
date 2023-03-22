@extends('layouts.appauth')

@section('content')
<section class="bg-gray-50 dark:bg-gray-900">
    <div class="mx-auto flex flex-col items-center justify-center px-6 py-8 md:h-screen lg:py-0">
        <a href="#" class="mb-0 flex items-center text-2xl font-semibold text-gray-900 dark:text-white">
            <img class="mr-2 w-32" src="{{ asset('logo/tlc.png') }}" alt="logo">
        </a>
        <div class="w-full rounded-lg bg-white shadow dark:border dark:border-gray-600 dark:bg-gray-800 sm:max-w-md md:mt-0 xl:p-0">
            <div class="space-y-4 p-6 sm:p-8 md:space-y-6">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                    Sign up to your account
                </h1>
                <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div>
                        <label for="first_name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your First Name</label>
                        <input type="text" name="first_name" id="first_name" class="@error('first_name') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your
                            Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="@error('last_name') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your
                            email</label>
                        <input type="email" name="email" id="email" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" placeholder="name@company.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="@error('password') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" required autocomplete="new-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label for="password-confirm" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password_confirmation" id="password-confirm" placeholder="••••••••" class="@error('password-confirm') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" required autocomplete="new-password">
                        @error('password-confirm')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-primary-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Register</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-300">
                        You have an account yet? <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
