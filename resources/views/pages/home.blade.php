<?php

use function Livewire\Volt\{state, mount, layout, title};

state(['version', 'page']);

layout('layouts.home');

title(__('Welcome to Doczilla'));

mount(function () {
    $this->version = config('doczilla.versions.default');
    $this->page = config('doczilla.docs.landing');
});

?>

<div>
  <div class="fixed top-0 right-0 z-50 hidden px-6 py-4 sm:block">
    <x-theme-toggle />
  </div>

  <div class="relative flex flex-col justify-center min-h-screen sm:items-center sm:pt-0">
    <div class="max-w-6xl mx-auto md:px-6 lg:px-8">
      <div class="flex flex-col justify-center md:justify-start md:pt-0">
        <img id="logo" src="/storage/brand/splash.svg" class="w-[400px] md:w-[800px]" alt="Doczilla" />
        <div class="reflection h-[50px] w-[400px] md:h-[100px] md:w-[800px]" />
      </div>

      <x-section-border class="pt-0 pb-10" size="xs" is-home />

      <div class="flex items-center justify-start space-x-4">
        <a href="{{ route('docs.show', [$version, $page]) }}" wire:navigate
          class="px-6 py-2 text-xl font-medium text-white rounded-full bg-primary-500 hover:bg-primary-600 dark:bg-primary-600 dark:hover:bg-primary-700">
          {{ __('Get Started') }}
        </a>
        <a href="https://github.com/jetstreamlabs/doczilla"
          class="text-xl font-medium leading-6 text-gray-900 hover:text-warning-500 dark:text-gray-200 dark:hover:text-warning-500"
          target="_blank">
          {{ __('View on GitHub') }} <span aria-hidden="true">→</span>
        </a>
      </div>

      <div class="flex justify-center mt-20 md:items-center md:justify-between">
        <div class="text-sm text-center text-gray-500 md:text-left">{!! copyright() !!}</div>

        <div class="hidden ml-4 text-sm text-right text-gray-500 md:ml-0 md:block">
          {{ version() }}
        </div>
      </div>
    </div>
  </div>
</div>
