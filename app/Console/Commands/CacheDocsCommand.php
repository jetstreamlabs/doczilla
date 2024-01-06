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

namespace App\Console\Commands;

use App\Contracts\ContentCachingService;
use Illuminate\Console\Command;

class CacheDocsCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'docs:cache';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Cache all of our documentation pages.';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    if (config('doczilla.cache.enabled')) {
      app(ContentCachingService::class)->make();

      $this->info('All documentation has been cached.');

      return 0;
    }

    $this->error("Docs caching isn't enabled. Check your .env or doczilla config.");

    return 1;
  }
}
