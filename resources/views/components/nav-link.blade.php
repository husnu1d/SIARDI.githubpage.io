 @props(['dashboardActive'])

@php
$classes = ($dashboardActive ?? false)
            ? 'flex items-center px-4 py-2 rounded-md bg-gray-700'
            : 'flex items-center px-4 py-2 rounded-md hover:bg-gray-700';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }} >
                  {{ $slot }}
</a>
