@props(['searchIcon','searchIconClasses','searchIconOtherAttributes'])
<div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
    @svg($searchIcon, 'w-5 h-5 text-gray-400 dark:text-gray-500', $searchIconOtherAttributes)
</div>
