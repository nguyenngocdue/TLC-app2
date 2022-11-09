<div class="flex items-center text-sm">
    <!-- Avatar with inset shadow -->
    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
        <img class="object-cover w-full h-full rounded-full" src={{$avatar}} alt="" loading="lazy">
        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
    </div>
    <div>
        <p class="font-semibold">{{$title}}</p>
        <p class="text-xs text-gray-600 dark:text-gray-400">
            {{$description}}
        </p>
    </div>
</div>