@extends('layouts.appauth')

@section('content')
    <section class="bg-gray-50 dark:bg-gray-900">
    <div id="background-image" class="w-auto h-screen bg-cover transition-transform duration-500">
        <div class="mx-auto flex flex-col items-center justify-center px-6 py-8 md:h-screen lg:py-0">
            <div
            class="w-full rounded-lg bg-white shadow dark:border dark:border-gray-600 dark:bg-gray-800 sm:max-w-md md:mt-0 xl:p-0">
            <a href="/login" class="mt-5 flex items-center justify-center text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-32" src="{{ asset('logo/tlc.png') }}" alt="logo">

            </a>
            <div class="space-y-2 p-4 sm:p-6 md:space-y-4">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                        Reset Password
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your
                                email</label>
                            <input type="email" name="email" id="email"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="font-bold text-red-600" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="password"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                class="@error('password') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                required autocomplete="new-password">
                            @error('password')
                                <span class="font-bold text-red-600" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="password-confirm"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password-confirm" placeholder="••••••••"
                                class="@error('password-confirm') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                required autocomplete="new-password">
                            @error('password-confirm')
                                <span class="font-bold text-red-600" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full rounded-lg bg-primary-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Reset
                            Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
