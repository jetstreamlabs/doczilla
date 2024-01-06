<?php

use function Livewire\Volt\{state, mount};

state(['section', 'version', 'breadcrumbs']);

mount(function (string $currentSection, string $currentVersion, array $breadcrumbs) {
    $this->section = $currentSection;
    $this->version = $currentVersion;
    $this->breadcrumbs = $breadcrumbs;
});

$sidebarOpen = function () {
    $this->dispatch('toggleSidebarVisibility');
};

$paletteOpen = function () {
    $this->dispatch('open-search-palette');
};

?>

<div x-init="window.addEventListener('scroll', () => {
    stickyHeader = window.scrollY > 0;
});" x-data="{
    stickyHeader: false,
}"
  class="sticky top-0 z-10 flex flex-col pt-4 bg-white border-b border-gray-200 dark:border-gray-600 dark:bg-gray-800"
  :class="{ 'stickyHeader': stickyHeader }">
  <div class="flex flex-row pb-3 border-b border-gray-200 dark:border-gray-600">
    <button type="button"
      class="px-4 text-gray-400 border-r border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 dark:border-gray-600 dark:text-gray-600 lg:hidden"
      wire:click="sidebarOpen()">
      <span class="sr-only">Open sidebar</span>
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="w-6 h-6" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
      </svg>
    </button>
    <div class="flex justify-between flex-1 px-4 sm:px-6 lg:px-8">
      <button wire:click="paletteOpen()"
        class="inline-flex items-center justify-between w-full py-0 pl-3 pr-2 text-sm leading-5 text-gray-400 border border-gray-200 rounded-md shadow-sm whitespace-nowrap hover:border-gray-300 dark:border-gray-700 dark:text-gray-500 dark:hover:border-gray-600 sm:w-96"
        :class="stickyHeader ? 'bg-transparent' : 'bg-white dark:bg-gray-800'" aria-controls="search-modal">
        <div class="flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-4 h-4 mr-3" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
          </svg>

          <span>{{ __('Quick Search ...') }} </span>
        </div>
        <div class="flex items-center justify-center w-5 h-5 ml-3 font-medium text-gray-500 dark:text-gray-400">
          <kbd class="text-[length:1.2em]">⌘</kbd>
          <kbd class="ml-[2px] text-[length:0.9em]">{{ __('K') }}</kbd>
        </div>
      </button>

      <livewire:fuse-palette :version="$version" />

      <div class="flex items-center">
        @if (!empty(config('doczilla.docs.github')))
          <x-github />
        @endif
        @if (!empty(config('doczilla.docs.twitterx')))
          <x-twitterx />
        @endif
        <livewire:version-menu :current-section="$section" :current-version="$version" />
        <x-theme-toggle />
      </div>
    </div>
  </div>
  <x-breadcrumbs :pages="$breadcrumbs" :$version />
</div>
