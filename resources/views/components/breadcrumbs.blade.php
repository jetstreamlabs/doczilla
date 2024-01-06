@props(['pages' => $pages, 'version' => $version])

<nav class="flex px-8 py-4 dark:border-gray-600" aria-label="Breadcrumb">
  <ol role="list" class="flex items-center space-x-2">
    <li>
      <div>
        <a wire:navigate href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="flex-shrink-0 w-5 h-5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
          </svg>
          <span class="sr-only">{{ __('Home') }}</span>
        </a>
      </div>
    </li>
    <li>
      <div class="flex items-center">
        <svg class="flex-shrink-0 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <a wire:navigate href="{{ route('docs.show', $version) }}"
          class="ml-2 text-sm font-medium text-gray-400 hover:text-gray-500">{{ $version }}</a>
      </div>
    </li>
    @foreach ($pages as $page)
      <li>
        <div class="flex items-center">
          <svg class="flex-shrink-0 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
          </svg>
          @if ($page['route'] === 'last')
            <a class="ml-2 text-sm font-medium text-gray-600 disabled hover:text-gray-600 dark:text-primary-400 dark:hover:text-primary-400"
              disabled>{{ $page['text'] }}</a>
          @else
            <a wire:navigate href="{{ $page['route'] }}"
              class="ml-2 text-sm font-medium text-gray-400 hover:text-gray-500">{{ $page['text'] }}</a>
          @endif
        </div>
      </li>
    @endforeach
  </ol>
</nav>
