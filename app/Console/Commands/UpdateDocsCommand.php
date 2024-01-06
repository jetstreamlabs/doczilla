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

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class UpdateDocsCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'docs:update';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update the current documentation.';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    if (config('doczilla.update_dependencies')) {
      $this->info('Updating composer packages ... ');
      Process::run('composer update');
    }

    Process::run('php artisan docs:latest');

    if (config('doczilla.update_dependencies')) {
      $this->info('Updating node packages ... ');
      Process::run('npm update');
    }

    $this->info('Caching documentation ... ');
    Process::run('php artisan docs:cache');

    $this->info('Indexing documentation ... ');
    Process::run('php artisan docs:index');

    $this->info('Building application ... ');
    Process::run('npm run build');

    $this->info('Documents have been updated and your app has been rebuilt.');
  }
}
