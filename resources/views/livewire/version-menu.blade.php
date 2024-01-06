<?php

use function Livewire\Volt\{state, mount};

state(['page', 'versions', 'currentVersion']);

mount(function (string $currentSection, string $currentVersion) {
    $this->page = $currentSection;
    $this->versions = config('doczilla.versions.published');
    $this->currentVersion = $currentVersion;
});

$switch = function ($version) {
    $route = route('docs.show', [
        'version' => $version,
        'page' => $this->page,
    ]);

    $this->redirect($route, navigate: true);
};

?>

<x-dropdown align="right" width="20">
  <x-slot name="trigger">
    <span class="inline-flex rounded-md">
      <button type="button"
        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition bg-transparent rounded-md hover:bg-gray-200 hover:text-gray-700 focus:outline-none dark:text-gray-200 dark:hover:bg-gray-900 dark:hover:text-gray-100 dark:focus:bg-gray-900 dark:active:bg-gray-900">
        v{{ $currentVersion }}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="-mr-0.5 ml-2 h-4 w-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
        </svg>
      </button>
    </span>
  </x-slot>
  <x-slot name="content">
    @if ($versions)
      <div class="w-20">
        @foreach ($versions as $version)
          <x-dropdown-link :href="route('docs.show', [$version, $page])">
            <div class="flex items-center cursor-pointer">
              <div>{{ $version }}</div>
              @if ($version === $currentVersion)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-5 h-5 ml-2 text-primary-500">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
              @endif
            </div>
          </x-dropdown-link>
        @endforeach
      </div>
    @endif
  </x-slot>
</x-dropdown>
