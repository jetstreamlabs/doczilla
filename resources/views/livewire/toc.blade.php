<?php

use function Livewire\Volt\{state, mount};

state(['toc']);

mount(function ($toc) {
    $this->toc = $toc;
});

?>

<div id="toc"
  class="hidden px-4 pt-4 mt-8 mr-6 rounded shadow-lg lg:w-60 bg-warning-50 dark:bg-gray-800 dark:text-gray-100 shrink-0 lg:block">
  <h2 class="mb-3 text-sm font-semibold text-gray-600 uppercase dark:text-gray-400">
    {{ __('On This Page') }}
  </h2>
  @if ($toc && count($toc) > 0)
    <ul class="divide-y divide-gray-200 divide-dashed dark:divide-gray-600">
      @foreach ($toc as $link)
        <li class="py-3">
          <a href="{{ $link['href'] }}"
            class="ml-1 text-sm text-gray-600 transition-all hover:text-warning-500 dark:text-gray-300 dark:hover:text-gray-100">
            {{ mb_strimwidth($link['text'], 0, 25, ' ...') }}
          </a>
        </li>
      @endforeach
    </ul>
  @else
    <ul>
      <li class="py-3 text-sm text-gray-600 dark:text-gray-300">
        {{ __('Nothing Yet ...') }}
      </li>
    </ul>
  @endif
</div>
