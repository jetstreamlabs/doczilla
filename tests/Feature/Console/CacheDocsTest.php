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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

it('caches docs when caching is enabled', function () {
  Config::set('doczilla.cache.enabled', true);

  $this->mock(ContentCachingService::class, function ($mock) {
    $mock->shouldReceive('make')->once();
  });

  $exitCode = Artisan::call('docs:cache');

  expect(Artisan::output())->toContain('All documentation has been cached.');
  expect(Artisan::output())->not()->toContain("Docs caching isn't enabled. Check your .env or doczilla config.");
  expect($exitCode)->toBe(0);
});

it('does not cache docs when caching is disabled', function () {
  Config::set('doczilla.cache.enabled', false);

  $exitCode = Artisan::call('docs:cache');

  expect(Artisan::output())->toContain("Docs caching isn't enabled. Check your .env or doczilla config.");
  expect($exitCode)->toBe(1);
});
