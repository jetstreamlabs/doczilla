<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <script>
      if (typeof(Storage) !== "undefined") {
        const theme = localStorage.getItem('theme') || 'system';
        if (theme === 'system') {
          const pref = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
          document.documentElement.classList.add(pref);
        } else {
          document.documentElement.classList.add(theme);
        }
      }
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <meta name="description" content="@yield('description')" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/brand/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/brand/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/brand/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('storage/brand/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('storage/brand/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#44403c">
    <meta name="theme-color" content="#ffffff">

    @livewireStyles()
    @stack('styles')
    @vite(['resources/js/app.js'])
  </head>

  <body class="font-sans antialiased bg-white dark:bg-gray-900">
    {{ $slot }}

    @livewireScriptConfig
  </body>

</html>
