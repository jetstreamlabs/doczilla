<div x-data="{ open: @entangle('show'), filtered: @entangle('filteredResults') }" @keydown.escape.window="open = false" @keydown.window.ctrl.k="open = true"
  @keydown.window.cmd.k="open = true" class="relative z-50 w-auto h-auto">
  <template x-teleport="body">
    <div x-show="open" class="fixed top-0 left-0 z-[99] flex items-start justify-center w-screen h-screen" x-cloak>
      <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open=false"
        class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
      <div x-show="open" x-trap.inert.noscroll="open" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full mt-24 bg-gray-100 dark:bg-gray-800 sm:max-w-xl sm:rounded-xl">
        <div x-data="{ focusedIndex: @entangle('focusedItemIndex'), focusedIndex: -1 }"
          @keydown.arrow-down.prevent="focusedIndex = focusedIndex === -1 ? 0 : (focusedIndex + 1) % {{ count($filteredResults) }}"
          @keydown.arrow-up.prevent="focusedIndex = focusedIndex === -1 ? {{ count($filteredResults) }} - 1 : (focusedIndex + {{ count($filteredResults) }} - 1) % {{ count($filteredResults) }}"
          @keydown.enter="$wire.performAction(focusedIndex)" class="relative w-full">

          <section id="search-input-container">
            <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20"
              fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                clip-rule="evenodd" />
            </svg>
            <input wire:model.live="query" type="text" wire:keydown.ArrowUp=""
              class="w-full h-12 pr-4 text-gray-900 bg-transparent border-0 dark:text-gray-400 pl-11 placeholder:text-gray-400 focus:ring-0 sm:text-sm"
              placeholder="{{ !$hasIndex ? 'Error: Search index file not found.' : 'Type to search' }}" role="combobox"
              aria-expanded="false" aria-controls="options" {{ !$hasIndex ? 'disabled' : '' }}>
          </section>

          <template x-if="filtered.length > 0">
            <div class="x-cloak w-full bg-gray-300 mb-2 dark:bg-gray-600 h-[1px]"></div>
          </template>

          <ul class="px-2 text-sm text-gray-700 dark:text-gray-400" id="options" role="listbox" tabindex="0">
            @foreach ($filteredResults as $index => $result)
              <li class="flex items-center px-3 py-2 mb-2 rounded-md cursor-pointer select-none hover:bg-primary-300"
                :class="{ 'bg-primary-300 text-gray-900': focusedIndex === {{ $index }} }"
                wire:click="performAction(focusedIndex)" role="option">
                <div class="flex flex-row items-start justify-between">
                  <div class="w-full min-w-min">
                    <div class="font-bold">{{ $result['item']['title'] }}</div>
                    <div class="text-sm">{!! mb_strimwidth($result['item']['content'], 0, 160, ' ...') !!}</div>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
          @if ($query && count($filteredResults) === 0)
            <div class="w-full bg-gray-300 dark:bg-gray-600 h-[1px]"></div>
            <p class="p-4 text-sm text-gray-800 dark:text-gray-400">{{ __('No results found.') }}</p>
          @endif
        </div>
        <div
          class="flex justify-between items-center bg-gray-200 dark:bg-gray-900 px-4 py-2.5 text-xs text-gray-700 dark:text-gray-400 rounded-bl-xl rounded-br-xl shadow-xl">
          <div class="flex flex-wrap items-center">
            <div class="flex flex-wrap items-center">
              <kbd
                class="flex items-center justify-center w-5 h-5 font-semibold text-black bg-white border border-gray-400 rounded dark:text-white dark:bg-gray-600 dark:border-gray-500">&uarr;</kbd>
              <kbd
                class="flex items-center justify-center w-5 h-5 ml-[3px] font-semibold text-black bg-white border border-gray-400 rounded dark:text-white dark:bg-gray-600 dark:border-gray-500">&darr;</kbd>
              <span class="hidden ml-1 sm:inline">Navigate Results</span>
            </div>
            <div class="flex flex-wrap items-center ml-2">
              <kbd
                class="flex items-center justify-center w-12 h-5 font-semibold text-black bg-white border border-gray-400 rounded dark:text-white dark:bg-gray-600 dark:border-gray-500">enter</kbd>
              <span class="hidden ml-1 sm:inline">Select</span>
            </div>
          </div>
          <div class="flex flex-wrap items-center">
            <kbd
              class="flex items-center justify-center w-8 h-5 font-semibold text-black bg-white border border-gray-400 rounded dark:text-white dark:bg-gray-600 dark:border-gray-500">esc</kbd>
            <span class="hidden ml-1 sm:inline">Close</span>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>
