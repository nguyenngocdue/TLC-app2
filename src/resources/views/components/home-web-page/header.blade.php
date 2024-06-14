<header x-data="{navbarOpen: false}"
class="fixed z-50 flex w-full items-center text-white dark:bg-black bg-gradient-to-r from-blue-900 via-blue-500 to-blue-900 border-b">
      <div class="w-full mx-4">
        <div class="relative -mx-4 flex items-center justify-between w-full">
          <div class="w-60 max-w-full px-4">
            <a href="javascript:void(0)" class="block w-full">
              <img
                src="{{ asset('logo/moduqa-white.svg') }}"
                alt="logo"
                class="dark:hidden h-10 object-cover"
              />
              <img
                src="{{ asset('logo/moduqa-white.svg') }}"
                alt="logo"
                class="hidden dark:block h-1 object-cover"
              />
            </a>
          </div>
          <div class="flex w-full items-center justify-between px-4">
            <div>
              <button
                @click="navbarOpen = !navbarOpen"
                :class="navbarOpen && 'navbarTogglerActive' "
                id="navbarToggler"
                class="absolute right-4 top-1/2 block -translate-y-1/2 rounded-lg px-3 py-2 ring-primary focus:ring-2 lg:hidden"
              >
                <span
                  class="relative my-[6px] block h-[2px] w-[30px] bg-gray-500 dark:bg-white"
                ></span>
                <span
                  class="relative my-[6px] block h-[2px] w-[30px] bg-gray-500 dark:bg-white"
                ></span>
                <span
                  class="relative my-[6px] block h-[2px] w-[30px] bg-gray-500 dark:bg-white"
                ></span>
              </button>
              <nav
                :class="!navbarOpen && 'hidden' "
                id="navbarCollapse"
                class="absolute right-4 top-full w-full  max-w-[250px] rounded-lg lg:bg-transparent bg-blue-800 px-6 py-2 shadow dark:bg-dark-2 lg:static lg:block lg:w-full lg:max-w-full lg:shadow-none lg:dark:bg-transparent"
              >
                <ul class="block lg:flex">
                  @foreach($dataSource as $item)
                    <li class="flex items-center">
                      <a
                        href="#{{Str::slug($item)}}"
                        class="flex py-2 text-center items-center text-base text-shadow-lg font-medium hover:font-bold uppercase text-slate-200 hover:text-white  dark:text-slate-900 dark:hover:text-white lg:ml-12 lg:inline-flex"
                      >
                        {{$item}}
                      </a>
                    </li>
                  @endforeach
                </ul>
                <ul class="block lg:hidden border-t">
                    @auth
                        <li>
                            <a
                                href="/dashboard"
                                class="flex py-2 text-base text-shadow-lg font-medium hover:font-bold uppercase text-slate-200 hover:text-white  dark:text-slate-900 dark:hover:text-white lg:ml-12 lg:inline-flex"
                            >
                                Dashboard
                            </a>
                        </li>
                    @endauth
                    @guest
                    <li>
                        <a
                            href="/login"
                            class="flex py-2 text-base text-shadow-lg font-medium hover:font-bold uppercase text-slate-200 hover:text-white  dark:text-slate-900 dark:hover:text-white lg:ml-12 lg:inline-flex"
                        >
                            Login
                        </a>
                    </li>
                    <li>
                        <a
                            href="/register"
                            class="flex py-2 text-base text-shadow-lg font-medium hover:font-bold uppercase text-slate-200 hover:text-white  dark:text-slate-900 dark:hover:text-white lg:ml-12 lg:inline-flex"
                        >
                            Sign Up
                        </a>
                    </li>
                    @endguest
                </ul>
              </nav>
            </div>
            <div class="hidden justify-end pr-16 sm:flex lg:pr-0 gap-1">
            @auth
                <a
                    href="/dashboard"
                    class="rounded-md bg-blue-400 px-7 py-3 text-base font-medium text-white hover:bg-blue-800/90"
                >
                    Dashboard
                </a>
            @endauth
            @guest
                <a
                    href="/login"
                    {{-- class="px-7 py-3 text-base font-medium text-white hover:text-blue-800 dark:text-white" --}}
                    class="flex rounded-md text-center items-center bg-blue-400 px-7 py-3 text-base font-medium text-white hover:bg-blue-800/90"
                >
                    Login
                </a>
                <a
                    href="/register"
                    class="flex rounded-md text-center items-center bg-blue-400 px-7 py-3 text-base font-medium text-white hover:bg-blue-800/90"
                >
                    Sign Up
                </a>
            @endguest
            </div>

          </div>
        </div>
      </div>
</header>
