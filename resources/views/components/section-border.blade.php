@props([
    'size' => 'xl',
    'isHome' => false,
])

@php
  $sizes = [
      'xs' => 'py-1',
      'sm' => 'py-2',
      'md' => 'py-4',
      'lg' => 'py-6',
      'xl' => 'py-8',
  ][$size];

  $home = $isHome ? 'border-t border-gray-200 dark:border-gray-800' : 'border-t border-gray-200 dark:border-gray-600';

@endphp

<div class="hidden sm:block">
  <div {{ $attributes->merge(['class' => $sizes]) }}>
    <div class="{{ $home }}"></div>
  </div>
</div>
