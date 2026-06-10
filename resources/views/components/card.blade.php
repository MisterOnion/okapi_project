{{-- declare props for accepts --}}
@props(['highlight' => false])

<div @class(['highlight' => $highlight, 'card'])>
    {{ $slot }}
    {{-- lead id passed to component, fetch lead id as attribute 
    and show in component --}}
    <a href="{{ $attributes->get('href') }}" class="btn">View Details</a>
</div>