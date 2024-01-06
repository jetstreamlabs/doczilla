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

use App\Contracts\FuseIndexingService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('builds page stack correctly from content', function () {
  $service = app(FuseIndexingService::class);

  $pageStack = $service->buildPageStack();

  expect($pageStack)->toBeArray();

  foreach ($pageStack as $version => $pages) {
    expect($version)->toBeString();
    expect($pages)->toBeArray();

    foreach ($pages as $page) {
      expect($page)->toHaveKeys(['title', 'content', 'route']);
    }
  }
});

it('generates fuse index files correctly for each published version', function () {
  $tempDir = storage_path('app/doczilla_docs_test');
  File::makeDirectory($tempDir, 0777, true, true);

  $publishedVersions = config('doczilla.versions.published');

  foreach ($publishedVersions as $version) {
    $versionDir = $tempDir.DIRECTORY_SEPARATOR.$version;
    File::makeDirectory($versionDir, 0777, true, true);
  }

  if (! empty($publishedVersions)) {
    $firstVersionDir = $tempDir.DIRECTORY_SEPARATOR.$publishedVersions[0];
    $dummyFilePath = $firstVersionDir.DIRECTORY_SEPARATOR.'index.json';
    File::put($dummyFilePath, json_encode(['dummy' => 'data']));
  }

  $service = app(FuseIndexingService::class, ['outputPath' => $tempDir]);
  $service->generate();

  foreach ($publishedVersions as $version) {
    $expectedFilePath = $tempDir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'index.json';
    expect(File::exists($expectedFilePath))->toBeTrue();

    $content = json_decode(File::get($expectedFilePath), true);
    expect($content)->toBeArray();
  }

  $command = "rm -rf {$tempDir}";
  Process::run($command);
});

it('removes pre tags correctly', function () {
  $htmlContent = '<div><pre>Example code</pre></div>';

  $service = app(FuseIndexingService::class);
  $normalizedContent = $service->normalizeContent($htmlContent);

  expect($normalizedContent)->not()->toContain('<pre>');
  expect($normalizedContent)->not()->toContain('</pre>');
});

it('sanitaizes embed iframes for indexing', function () {
  $htmlContent = '<iframe width="560" height="315" src="https://www.youtube.com/embed/jGb5zIgwL4c?si=24Lvt8kukqpEPITa" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';

  $service = app(FuseIndexingService::class);
  $normalizedContent = $service->normalizeContent($htmlContent);

  expect($normalizedContent)->not()->toContain('<iframe');
  expect($normalizedContent)->not()->toContain('</iframe>');
});
