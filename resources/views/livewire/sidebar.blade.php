<?php

use function Livewire\Volt\{state, mount};

state(['navigation', 'currentPage']);

mount(function (array $sidebar, string $currentSection) {
    $this->navigation = $sidebar;
    $this->currentPage = $currentSection;
});

?>

<div
  class="hidden dark:border-gray-700 dark:bg-gray-950 lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-gray-200 lg:bg-gray-100 lg:pb-4 lg:pt-5">
  <div class="flex items-center flex-shrink-0 px-6">
    <a wire:navigate href="{{ route('home') }}" class="flex items-center">
      <x-application-logo class="w-12 h-12" />
      <span class="ml-3 text-2xl text-gray-800 dark:text-gray-400">
        RAWWRRR!
      </span>
    </a>
  </div>
  <!-- Sidebar component, swap this element with another sidebar if you like -->
  <div class="flex flex-col flex-1 h-0 pt-1 mt-5 overflow-y-auto">
    <nav class="h-screen pl-4 text-sm">
      <ul class="space-y-9">
        @foreach ($navigation as $c => $categories)
          <li class="pl-3">
            <h2 class="font-medium text-gray-900 uppercase dark:text-white">
              {{ $c }}
            </h2>
            <ul
              class="mt-2 space-y-2 border-l-2 border-gray-200 dark:border-gray-800 lg:mt-4 lg:space-y-2 lg:border-gray-200">
              @foreach ($categories as $link)
                @php
                  $pageClass = $link['uri'] == $currentPage ? 'font-semibold text-primary-500 before:bg-primary-500' : 'text-gray-500 before:hidden before:bg-gray-300 hover:text-gray-600 hover:before:block dark:text-gray-400 dark:before:bg-gray-700 dark:hover:text-gray-300';
                @endphp
                <li class="relative">
                  <a wire:navigate
                    href="{{ route('docs.show', [
                        'version' => $link['version'],
                        'page' => $link['uri'],
                    ]) }}"
                    class="block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-y-1/2 before:rounded-full {{ $pageClass }}">
                    {{ mb_strimwidth($link['title'], 0, 25, ' ...') }}
                  </a>
                </li>
              @endforeach
            </ul>
          </li>
        @endforeach
      </ul>
    </nav>
  </div>
</div>
