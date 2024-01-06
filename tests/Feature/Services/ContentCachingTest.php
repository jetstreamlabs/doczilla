<?php

/**
 * Copyright (c) Jetstream Labs, LLC. All Rights Reserved.
 *
 * This software is licensed under the MIT License and free to use,
 * guided by the included LICENSE file.  For any required original
 * licenses, see the storage/licenses directory.
 *
 * Made with ♥ in the QC.
 */

use App\Services\ContentCachingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

it('checks that all pages in a navigation file are present', function () {
  $service = app(ContentCachingService::class);
  $pages = $service->getAllPages();

  foreach ($pages as $item) {
    $version = $item['version'];
    $page = $item['page'];

    $path = config('doczilla.docs.path').
      DIRECTORY_SEPARATOR.
      $version.
      DIRECTORY_SEPARATOR.
      $page.'.md';

    expect(File::exists($path))->toBeTrue();
  }
});

it('caches a subset of content pages using mocked cache', function () {
  Config::set('doczilla.cache.enabled', true);

  Cache::shouldReceive('remember')
      ->times(5)
      ->withAnyArgs()
      ->andReturnUsing(function ($key, $time, $callback) {
        return $callback();
      });

  $service = app(ContentCachingService::class);

  $testPages = array_slice($service->getAllPages(), 0, 5);

  $service->make();

  foreach ($testPages as $item) {
    $version = $item['version'];
    $page = $item['page'];
    $key = md5("docs:{$version}:{$page}");

    Cache::shouldHaveReceived('remember')
         ->with($key, config('doczilla.cache.period'), \Closure::class)
         ->once();
  }
});
