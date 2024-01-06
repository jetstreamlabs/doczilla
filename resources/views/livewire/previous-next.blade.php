<?php

use function Livewire\Volt\{state, mount};

state(['previous', 'next']);

mount(function ($prevPage, $nextPage) {
    $this->previous = $prevPage;
    $this->next = $nextPage;
});

?>

<div class="items-center justify-between pt-8 space-y-6 sm:flex sm:space-x-4 sm:space-y-0">
  @if ($previous)
    <div class="flex-col items-start sm:flex sm:w-1/2">
      <div>
        <div class="mb-1 pl-4 text-xs font-[650] uppercase text-primary-500">
          {{ __('Previous') }}
        </div>
        <div>
          <a wire:navigate href="{{ $previous['link'] }}"
            class="flex items-center font-medium text-gray-800 dark:text-gray-200">
            <svg class="mr-2 rotate-180 shrink-0 fill-gray-400 dark:fill-gray-500" width="8" height="10"
              xmlns="http://www.w3.org/2000/svg">
              <path d="M1 2 2.414.586 6.828 5 2.414 9.414 1 8l3-3z" />
            </svg>
            <span>{{ $previous['title'] }}</span>
          </a>
        </div>
      </div>
    </div>
  @endif
  @if ($next)
    <div class="flex-col items-end ml-auto sm:flex sm:w-1/2">
      <div>
        <div class="mb-1 pr-4 text-right text-xs font-[650] uppercase text-primary-500">
          {{ __('Next') }}
        </div>
        <a wire:navigate href="{{ $next['link'] }}"
          class="flex items-center font-medium text-gray-800 dark:text-gray-200">
          <span>{{ $next['title'] }}</span>
          <svg class="ml-2 shrink-0 fill-gray-400 dark:fill-gray-500" width="8" height="10"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M1 2 2.414.586 6.828 5 2.414 9.414 1 8l3-3z" />
          </svg>
        </a>
      </div>
    </div>
  @endif
</div>
