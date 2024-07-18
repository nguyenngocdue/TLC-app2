@php
  $src = asset('images/homepage-what-it-does/001-production.webp');
@endphp
<footer
      class="relative z-10 text-black pb-10 pt-20 dark:bg-dark lg:pb-20 lg:pt-[120px]"
      style="background-image: url('{{$src}}'); opacity: 0.7;"
      {{-- class="relative z-10 text-white text-shadow-md pb-10 pt-20 dark:bg-dark lg:pb-20 lg:pt-[120px] bg-gradient-to-r from-blue-900 via-blue-500 to-blue-900" --}}
    >
      <div class="container1 bg-white mx-32 pt-10 px-20 rounded">
        <div class="-mx-4 flex flex-wrap">
          <div class="w-full px-4 sm:w-2/3 lg:w-3/12">
            <div class="mb-10 w-full">
              <a
                href="javascript:void(0)"
                class="mb-6 inline-block max-w-[160px]"
              >
                <img
                  src="{{ asset('logo/moduqa.svg') }}"
                  alt="logo"
                  class="max-w-full dark:hidden"
                />
                <img
                  src="{{ asset('logo/moduqa.svg') }}"
                  alt="logo"
                  class="hidden max-w-full dark:block"
                />
              </a>
              <p class="mb-7 text-base text-body-color dark:text-dark-6">
                Transforming Horizons: Pioneering Modular Manufacturing and Construction, Where Every Project Embodies Innovation, Efficiency, and the Path Towards a Sustainable Future.
              </p>              
            </div>
          </div>
          <div class="w-full px-4 sm:w-1/2 lg:w-2/12">
            <div class="mb-10 w-full">
              <h4 class="mb-9 text-lg font-semibold text-dark dark:text-white">
                Resources
              </h4>
              <ul class="space-y-3">
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    SaaS Development
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Our Products
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    User Flow
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    User Strategy
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="w-full px-4 sm:w-1/2 lg:w-2/12">
            <div class="mb-10 w-full">
              <h4 class="mb-9 text-lg font-semibold text-dark dark:text-white">
                Company
              </h4>
              <ul class="space-y-3">
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    About US
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Contact & Support
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Success History
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Setting & Privacy
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="w-full px-4 sm:w-1/2 lg:w-2/12">
            <div class="mb-10 w-full">
              <h4 class="mb-9 text-lg font-semibold text-dark dark:text-white">
                Quick Links
              </h4>
              <ul class="space-y-3">
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Premium Support
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Our Services
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Know Our Team
                  </a>
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="inline-block text-base leading-loose text-body-color hover:text-primary dark:text-dark-6"
                  >
                    Access App
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="w-full px-4 sm:w-1/2 lg:w-3/12">
            <div class="mb-10 w-full">
              <h4 class="mb-9 text-lg font-semibold text-dark dark:text-white">
                Follow Us On
              </h4>
              <div class="mb-6 flex items-center">
                <a
                  href="https://youtube.com/@moduqa"
                  target="_blank"
                  class="mr-3 flex h-8 w-8 items-center justify-center rounded-full border border-stroke text-dark hover:border-primary hover:bg-primary dark:border-dark-3 dark:text-white dark:hover:border-primary sm:mr-4 lg:mr-3 xl:mr-4 hover:text-red-800"
                >
                  <svg
                    width="16"
                    height="12"
                    viewBox="0 0 16 12"
                    class="fill-current"
                  >
                    <path
                      d="M15.6645 1.88018C15.4839 1.13364 14.9419 0.552995 14.2452 0.359447C13.0065 6.59222e-08 8 0 8 0C8 0 2.99355 6.59222e-08 1.75484 0.359447C1.05806 0.552995 0.516129 1.13364 0.335484 1.88018C0 3.23502 0 6 0 6C0 6 0 8.79263 0.335484 10.1198C0.516129 10.8664 1.05806 11.447 1.75484 11.6406C2.99355 12 8 12 8 12C8 12 13.0065 12 14.2452 11.6406C14.9419 11.447 15.4839 10.8664 15.6645 10.1198C16 8.79263 16 6 16 6C16 6 16 3.23502 15.6645 1.88018ZM6.4 8.57143V3.42857L10.5548 6L6.4 8.57143Z"
                    />
                  </svg>
                </a>
                <a
                  href="https://www.linkedin.com/company/moduqa"
                  target="_blank"
                  class="mr-3 flex h-8 w-8 items-center justify-center rounded-full border border-stroke text-dark hover:border-primary hover:bg-primary hover:text-blue-800 dark:border-dark-3 dark:text-white dark:hover:border-primary sm:mr-4 lg:mr-3 xl:mr-4"
                >
                  <svg
                    width="14"
                    height="14"
                    viewBox="0 0 14 14"
                    class="fill-current"
                  >
                    <path
                      d="M13.0214 0H1.02084C0.453707 0 0 0.451613 0 1.01613V12.9839C0 13.5258 0.453707 14 1.02084 14H12.976C13.5432 14 13.9969 13.5484 13.9969 12.9839V0.993548C14.0422 0.451613 13.5885 0 13.0214 0ZM4.15142 11.9H2.08705V5.23871H4.15142V11.9ZM3.10789 4.3129C2.42733 4.3129 1.90557 3.77097 1.90557 3.11613C1.90557 2.46129 2.45002 1.91935 3.10789 1.91935C3.76577 1.91935 4.31022 2.46129 4.31022 3.11613C4.31022 3.77097 3.81114 4.3129 3.10789 4.3129ZM11.9779 11.9H9.9135V8.67097C9.9135 7.90323 9.89082 6.8871 8.82461 6.8871C7.73571 6.8871 7.57691 7.74516 7.57691 8.60323V11.9H5.51254V5.23871H7.53154V6.16452H7.55423C7.84914 5.62258 8.50701 5.08065 9.52785 5.08065C11.6376 5.08065 12.0232 6.43548 12.0232 8.2871V11.9H11.9779Z"
                    />
                  </svg>
                </a>
              </div>
              <p class="text-base text-body-color dark:text-dark-6">
                {{-- Hide this until company get copyright --}}
                {{-- &copy; 2024 All rights reserved. --}}
                Version {{config("version.app_version")}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </footer>