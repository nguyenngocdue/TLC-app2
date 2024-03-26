<section id="why-its-cool" class="items-center bg-black">
    <div class="flex items-center justify-center">
        <h2 class="text-4xl md:text-6xl font-bold text-yellow-600 py-5 lg:py-14">A Simple Process with Colossal Results.</h2>
    </div>
    <div class="mx-10 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div >
            <div class="w-full flex justify-center items-center py-5 lg:py-10">
                <img src="/images/homepage-why-its-cool/SimpleToImplement.webp" alt="" class="w-80 h-2/4 object-cover rounded-2xl">
            </div>
            <div>
                <div >
                    <h4 class="text-center text-2xl font-bold mb-9 text-yellow-600">
                    Simple to implement.
                    </h4>
                    <p class="text-left text-base text-white whitespace-pre-wrap">Only you need {{env('APP_NAME')}}. There’s nothing for your guest to download!

Your client will instantly connect to {{env('APP_NAME')}} on their phone’s web browser through the link you send them.
                    </p>
                </div>
            </div>
        </div>
        <div >
            <div class="w-full flex justify-center items-center py-5 lg:py-10">
                <img src="/images/homepage-why-its-cool/EasyToUse.webp" alt="" class="w-80 h-2/4 object-cover rounded-2xl">
            </div>
            <div>
                <div >
                    <h4 class="text-center text-2xl mb-9 font-bold text-yellow-600">
                    Easy to use.
                    </h4>
                    <p class="text-left text-base text-white whitespace-pre-wrap">Matrix Mode provides a comprehensive overview of entire project progress, ensuring all team members are aligned and informed.
                    </p>
                </div>
            </div>
        </div>
        <div >
            <div class="w-full flex justify-center items-center py-5 lg:py-10">
                <img src="/images/homepage-why-its-cool/SercuredInfo.webp" alt=""
                        class="w-80 h-2/4 object-cover rounded-2xl"
                >
            </div>
            <div>
                <div >
                    <h4 class="text-center text-2xl mb-9 font-bold text-yellow-600">
                    Keep information secure.
                    </h4>
                    <p class="text-left text-base text-white whitespace-pre-wrap">Your resources are safely housed in the cloud, ready for future access and system integration, with API access available for our Enterprise-level customers.

Our approach guarantees that all exchanges and the gathering of resources are both consensual and secure, ensuring no unauthorized access to another’s device, contact list, browsing history, or passwords.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-between py-10 lg:py-20 mx-20 lg:mx-28">
        <div>
            <h3 class="whitespace-pre-wrap text-white text-3xl">{{env('APP_NAME')}} is used by many different industries to connect people instantly - no matter where they are.</h3>
        </div>
        <div >
            <x-landing-page.button>User Cases</x-landing-page.button>
            <div class="py-2"></div>
            <x-landing-page.button>Try for free</x-landing-page.button>
            
        </div>
        
    </div>
</section>