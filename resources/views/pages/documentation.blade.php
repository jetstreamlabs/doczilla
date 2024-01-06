<?php

use function Livewire\Volt\{state, mount, layout, title};

state([
    'title' => '',
    'description' => '',
    'keywords' => '',
    'canonical' => '',
    'toc' => [],
    'sidebar' => [],
    'versions' => [],
    'nextPage' => [],
    'prevPage' => [],
    'currentVersion' => 0,
    'currentSection' => 0,
    'status' => 200,
    'content' => '',
    'breadcrumbs' => [],
]);

layout('layouts.main');

mount(function (string $version, ?string $page = null) {
    if (is_null($page)) {
        $page = config('doczilla.docs.landing');
    }

    $key = md5("docs:{$version}:{$page}");

    if (config('doczilla.cache.enabled')) {
        $data = cache()->has($key) ? cache()->get($key) : app('docs')->show($version, $page);
    } else {
        $data = app('docs')->show($version, $page);
    }

    $breadcrumbs = app('breadcrumbs');
    $breadcrumbs->add($data['title'], 'last');
    $this->breadcrumbs = $breadcrumbs->render();

    $this->title = $data['title'];
    $this->description = $data['description'];
    $this->keywords = $data['keywords'];
    $this->canonical = $data['canonical'];
    $this->toc = $data['toc'];
    $this->sidebar = $data['sidebar'];
    $this->versions = $data['versions'];
    $this->nextPage = $data['nextPage'];
    $this->prevPage = $data['prevPage'];
    $this->currentVersion = $data['currentVersion'];
    $this->currentSection = $data['currentSection'];
    $this->status = $data['status'];
    $this->content = $data['content'];
});

title(function () {
    return $this->title;
});

?>

<div id="docs" class="min-h-screen">
  @section('description', $description)
  @section('keywords', $keywords)
  @section('canonical', $canonical)

  <livewire:mobile :sidebar="$sidebar" :current-section="$currentSection" />

  <livewire:sidebar :sidebar="$sidebar" :current-section="$currentSection" />

  <div class="flex flex-col min-h-screen lg:pl-64">
    <livewire:navbar :current-section="$currentSection" :current-version="$currentVersion" :breadcrumbs="$breadcrumbs" />

    <main class="flex-1 pb-6 dark:bg-gray-900">
      <div class="flex items-start justify-center lg:justify-between">
        <div class="w-full pl-8 pr-12 mt-8 prose dark:prose-invert prose-headings:font-normal">

          <div class="docz">{!! $content !!}</div>

          @if ($status !== 404)
            <x-section-border size="xl" class="mt-6" />
            <livewire:previous-next :prev_page="$prevPage" :next_page="$nextPage" />
          @endif

        </div>

        <livewire:toc :toc="$toc" />
      </div>
    </main>
    <x-footer />
  </div>
  <x-scroll-top />
</div>
