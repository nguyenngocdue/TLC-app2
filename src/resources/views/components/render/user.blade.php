@props(['attachment', 'model', 'relationship'])
<div class="flex items-center text-sm">
    <!-- Avatar with inset shadow -->
    <div class="relative mr-3 hidden h-8 w-8 rounded-full md:block">
        <img class="h-full w-full rounded-full object-cover"
            src="https://wp.tlcmodular.com/wp-content/uploads/2022/07/bfdc18a057769428cd67-150x150.jpg" alt="img"
            loading="lazy" />
        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
    </div>
    <div>
        {{-- <p class="font-semibold">{{($user->{$item['column_name']}->name_rendered)}}</p>
      <p class="text-xs text-gray-600 dark:text-gray-400">
        {{($user->{$item['column_name']}->email)}}
      </p> --}}
    </div>
</div>
