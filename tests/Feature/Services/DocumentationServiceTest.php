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

use App\Contracts\DocumentationService;
use Illuminate\Support\Collection;

it("returns the default page if the version isn't published", function () {
  $service = app(DocumentationService::class);
  $version = '3.0';

  $defaultVersion = config('doczilla.versions.default');
  $defaultPage = config('doczilla.docs.landing');

  $route = route('docs.show', ['version' => $defaultVersion, 'page' => $defaultPage]);

  $response = $service->show($version);

  expect($response)->toBeInstanceOf(Collection::class);
  expect($response['route'])->toBe($route);
  expect($response['status'])->toBe(303);
});
