@props(['active', 'icon', 'link', 'name', 'parent', 'submenu'])

@php
$_parent = ($parent ?? false)
            ? 'has-sub'
            : '';
$classes1 = ($active ?? false)
            ? 'sidebar-item  active '.$_parent
            : 'sidebar-item '.$_parent;
$classes2 = ($active ?? false)
            ? 'submenu-item  active'
            : 'submenu-item';
@endphp

@if (($submenu ?? false))
    <li class="{{$classes2}}">
        <a href="{{ $link }}">
            <i class="{{ $icon }}"></i>
            <span>{{ $name }}</span>
        </a>
    </li>
@else
    <li class="{{$classes1}}">
        <a href="{{ $link }}" class='sidebar-link'>
            <i class="{{ $icon }}"></i>
            <span>{!! $name !!}</span>
        </a>
        @if (($parent ?? false))
            <ul class="submenu">
                {{ $slot }}
            </ul>
        @endif
    </li>
@endif
