@extends('layouts.appauth')

@section('content')
    <section class="bg-gray-50 dark:bg-gray-900">
    <div id="background-image" class="w-auto h-screen bg-cover transition-transform duration-500">
        <div class="mx-auto flex flex-col items-center justify-center px-6 py-8 md:h-screen lg:py-0">
            <div
            class="w-full rounded-lg bg-white shadow dark:border dark:border-gray-600 dark:bg-gray-800 sm:max-w-md md:mt-0 xl:p-0">
            <a href="/login" class="mt-5 justify-center flex items-center text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-32" src="{{ asset('logo/moduqa.svg') }}" alt="logo">
            </a>
                <div class="space-y-0 p-4 sm:p-6 md:space-y-2">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                        Reset Password
                    </h1>
                    @if (session('status'))
                        <div class="mb-4 flex rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-200 dark:text-green-800"
                            role="alert">
                            <svg aria-hidden="true" class="mr-3 inline h-5 w-5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Info</span>
                            <div>
                                <span class="font-medium">The reset password email is sent.</span> 
                                {{-- {{ session('status') }} --}}
                            </div>
                        </div>
                    @endif
                    <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your
                                email</label>
                            <input type="email" name="email" id="email"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                placeholder="name@company.com" value="{{ old('email') }}" required autocomplete="email"
                                autofocus>
                            @error('email')
                                <span class="font-bold text-red-600" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full rounded-lg bg-primary-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Send
                            Password Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
