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

namespace App\Services;

use App\Contracts\ContentCachingService as ContentCachingContract;
use App\Contracts\DocumentationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ContentCachingService implements ContentCachingContract
{
  public function __construct(
      protected DocumentationService $service
    ) {
  }

  /**
   * Cache all of our docs pages.
   *
   * @return void
   */
  public function make(): void
  {
    $pages = $this->getAllPages();

    // Limit the number of pages in the testing environment
    if (app()->environment('testing')) {
      $pages = array_slice($pages, 0, 5);
    }

    collect($pages)->each(function ($page) {
      $version = $page['version'];
      $section = $page['page'];

      $key = md5("docs:{$version}:{$section}");
      $time = config('doczilla.cache.period');

      Cache::remember($key, $time, function () use ($version, $section) {
        return $this->service->getPage($version, $section);
      });
    });
  }

  /**
   * Build an array of all current pages.
   *
   * @return array
   */
  public function getAllPages(): array
  {
    $dirs = collect(File::directories(config('doczilla.docs.path')));

    return $dirs->flatMap(function ($dir) {
      $file = File::get($dir.DIRECTORY_SEPARATOR.'navigation.json');
      $menus = collect(json_decode($file, true));

      return $menus->map(function ($values) {
        return collect($values)->map(function ($val) {
          return [
            'version' => $val['version'],
            'page' => $val['uri'],
          ];
        });
      })->flatten(1);
    })->all();
  }
}
