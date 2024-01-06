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

use App\Contracts\FuseIndexingService;
use Illuminate\Console\Command;

class RefreshFuseIndexesCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'docs:index';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Build Fuse indexes for all docs versions.';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    app(FuseIndexingService::class)->generate();

    $this->info('All documentation has been indexed for search.');
  }
}
