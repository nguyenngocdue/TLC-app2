@extends('layouts.appauth')

@section('content')
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto flex flex-col items-center justify-center px-6 py-8 md:h-screen lg:py-0">
            <a href="#" class="mb-0 flex items-center text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="mr-2 w-32" src="{{ asset('logo/tlc.png') }}" alt="logo">

            </a>
            <div
                class="w-full rounded-lg bg-white shadow dark:border dark:border-gray-700 dark:bg-gray-800 sm:max-w-md md:mt-0 xl:p-0">
                <div class="space-y-4 p-6 sm:p-8 md:space-y-6">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                        Sign in to your account
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your
                                email</label>
                            <input type="text" name="email" id="email"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                placeholder="name@company.com" value="{{ old('email') }}" required autocomplete="email"
                                autofocus>
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
                                required autocomplete="current-password">
                            @error('password')
                                <span class="font-bold text-red-600" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="remember" aria-describedby="remember" type="checkbox"
                                        class="focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-primary-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600"
                                        name="remember" {{ old('remember') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot
                                    password?</a>
                            @endif
                        </div>
                        <button type="submit"
                            class="w-full rounded-lg bg-primary-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign
                            in</button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Don’t have an account yet? <a href="{{ route('register') }}"
                                class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
