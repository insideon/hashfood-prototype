@props(['searchIcon','searchIconClasses','searchIconOtherAttributes'])
<div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
    @svg($searchIcon, 'w-4 h-4 sm:w-5 sm:h-5 text-gray-400 dark:text-gray-500', $searchIconOtherAttributes)
</div>
