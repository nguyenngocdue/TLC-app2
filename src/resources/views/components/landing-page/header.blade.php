<header x-data="{navbarOpen: false}"
class="fixed z-50 flex w-full items-center bg-white dark:bg-black bg-gradient-to-r from-cyan-200 via-blue-200 to-blue-300 border-b">
      <div class="container mx-auto">
        <div class="relative -mx-4 flex items-center justify-between">
          <div class="w-60 max-w-full px-4">
            <a href="javascript:void(0)" class="block w-full">
              <img
                src="{{ asset('logo/tlc.png') }}"
                alt="logo"
                class="dark:hidden h-10 object-cover"
              />
              <img
                src="{{ asset('logo/tlc.png') }}"
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
                class="absolute right-4 top-full w-full max-w-[250px] rounded-lg lg:bg-transparent bg-white px-6 py-2 shadow dark:bg-dark-2 lg:static lg:block lg:w-full lg:max-w-full lg:shadow-none lg:dark:bg-transparent"
              >
                <ul class="block lg:flex">
                  <li>
                    <a
                      href="javascript:void(0)"
                      class="flex py-2 text-base font-medium text-gray-500 hover:text-slate-800 dark:text-slate-900 dark:hover:text-white lg:ml-12 lg:inline-flex"
                    >
                      Home
                    </a>
                  </li>
                  <li>
                    <a
                      href="javascript:void(0)"
                      class="flex py-2 text-base font-medium text-gray-500 hover:text-slate-800 dark:text-slate-900 dark:hover:text-white lg:ml-12 lg:inline-flex"
                    >
                      Payment
                    </a>
                  </li>
                  <li>
                    <a
                      href="javascript:void(0)"
                      class="flex py-2 text-base font-medium text-gray-500 hover:text-slate-800 dark:text-slate-900 dark:hover:text-white lg:ml-12 lg:inline-flex"
                    >
                      Features
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="hidden justify-end pr-16 sm:flex lg:pr-0">
            @auth
                <a
                    href="/dashboard"
                    class="px-7 py-3 text-base font-medium text-slate-600 hover:text-blue-800 dark:text-white"
                >
                    Dashboard
                </a>
            @endauth
            @guest
                <a
                    href="/login"
                    class="px-7 py-3 text-base font-medium text-slate-600 hover:text-blue-800 dark:text-white"
                >
                    Login
                </a>
                <a
                    href="/register"
                    class="rounded-md bg-blue-600 px-7 py-3 text-base font-medium text-white hover:bg-blue-800/90"
                >
                    Sign Up
                </a>
                @endguest
            </div>
            
          </div>
        </div>
      </div>
</header>