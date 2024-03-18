@extends('layouts.appauth')

@section('content')
    <section class="bg-gray-50 dark:bg-gray-900">
        <div id="background-image" class="w-auto h-screen bg-cover bg-center transition-all duration-500">
            <div class="mx-auto flex items-center lg:justify-between lg:mr-20 justify-center px-6 py-8 md:h-screen lg:py-0">
                <div id="text-banner" class="mx-auto text-4xl text-white hidden lg:block lg:text-7xl">
                </div>
                <div
                class="w-full rounded-lg bg-white shadow dark:border dark:border-gray-600 dark:bg-gray-800 sm:max-w-md md:mt-0 xl:p-0">
                <a href="#" class="mt-5 flex items-center justify-center text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-32 items-center" src="{{ asset('logo/tlc.png') }}" alt="logo">
                </a>
                    <div class="space-y-2 p-4 sm:p-6 md:space-y-4">
                        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                            Sign in to your account
                        </h1>
                        <form class="space-y-2 md:space-y-4" method="POST" action="{{ route('login') }}">
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
                                <input type="hidden" id="time_zone" name="time_zone">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot
                                        password?</a>
                                @endif
                            </div>
                            <button type="submit"
                                class="w-full rounded-lg bg-primary-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign
                                in
                            </button>
                                
                            <!-- <div class="relative flex items-center justify-center">
                                    <div class="h-[1px] w-full bg-slate-600 dark:bg-white"></div>
                                    <div class="absolute w-10 bg-white text-center text-xs text-slate-700 dark:bg-slate-800">Or</div>
                            </div> -->
                            <!-- <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                            Sign in to your account
                            </h1> -->
                            <!-- <a href="{{route('login.google')}}" type="button" class="justify-between text-gray-900 w-full bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
                                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                    </svg>
                                    Login with Google (for non TLC users only)
                                    <div></div>
                            </a> -->
                            <div class="border-t"></div>
                            <p class="text-sm font-light text-gray-500 dark:text-gray-300">
                                Don’t have an account yet? <a href="{{ route('register') }}"
                                    class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up (for non TLC users only)</a>
                            </p>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </section>
    <script>
        var timezone = -(new Date().getTimezoneOffset()/60);
        document.getElementById('time_zone').value = timezone
    </script>
@endsection
