<?php

use function Livewire\Volt\{state, mount, on};

state(['navigation', 'currentPage', 'sidebarOpen' => false]);

mount(function (array $sidebar, string $currentSection) {
    $this->navigation = $sidebar;
    $this->currentPage = $currentSection;
});

on([
    'toggleSidebarVisibility' => function () {
        $this->sidebarOpen = !$this->sidebarOpen;
    },
]);

?>

<div x-data="{ open: @entangle('sidebarOpen') }" class="relative z-40 lg:hidden">
  <!-- Overlay -->
  <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>

  <!-- Sidebar -->
  <div class="fixed inset-0 z-40 flex" x-show="open">
    <div x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full"
      x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform"
      x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
      class="relative flex flex-col flex-1 w-full max-w-xs pt-5 pb-4 bg-gray-100 dark:bg-gray-900">
      <!-- Close button -->
      <div class="absolute top-0 right-0 pt-2 -mr-12">
        <button @click="open = false" type="button"
          class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
          <span class="sr-only">Close sidebar</span>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6 text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="flex items-center flex-shrink-0 px-4">
        <a wire:navigate href="{{ route('home') }}" class="flex items-center">
          <x-application-logo class="w-12 h-12" />
          <span class="ml-3 text-2xl text-gray-800 dark:text-gray-400">
            RAWWRRR!
          </span>
        </a>
      </div>
      <div class="flex-1 h-0 mt-5 overflow-y-auto">
        <nav class="h-screen px-2 text-sm">
          <ul class="space-y-9">
            @foreach ($navigation as $c => $categories)
              <li class="pl-3">
                <h2 class="font-medium text-gray-900 uppercase dark:text-white">
                  {{ $c }}
                </h2>
                <ul
                  class="mt-2 space-y-2 border-l-2 border-gray-200 dark:border-gray-800 lg:mt-4 lg:space-y-4 lg:border-gray-200">
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
                        @click.native="toggleSidebar(!sidebarOpen)"
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
    <div class="flex-shrink-0 w-14" aria-hidden="true"><!-- Dummy element --></div>
  </div>
</div>
