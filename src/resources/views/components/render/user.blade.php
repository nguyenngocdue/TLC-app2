@props(['src', 'name', 'email'])
<div class="flex items-center text-sm">
    <!-- Avatar with inset shadow -->
    <div class="relative mr-3 hidden h-8 w-8 rounded-full md:block">
        <img class="h-full w-full rounded-full object-cover" src="{{ $src }}" alt="img" loading="lazy" />
        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
    </div>
    <div>
        <p class="font-semibold">{{ $name }}</p>
        <p class="text-xs text-gray-600 dark:text-gray-400">
            {{ $email }}
        </p>
    </div>
</div>