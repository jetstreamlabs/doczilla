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

class SetupCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'docs:setup';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Setup the application and install the latest docs.';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    if (app()->environment('testing')) {
      $this->info('Testing environment detected, bypassing repository cloning.');
    } else {
      Process::run('php artisan docs:latest');
    }

    $this->info('Installing node packages ... ');
    Process::run('npm install');

    if (app()->environment('testing')) {
      $this->info('Testing environment detected, bypassing document caching and indexing.');
    } else {
      $this->info('Caching documentation ... ');
      Process::run('php artisan docs:cache');

      $this->info('Indexing documentation ... ');
      Process::run('php artisan docs:index');
    }

    $this->info('Building Doczilla ... ');
    Process::run('npm run build');

    $this->info('Application setup completed successfully. You should remove the setup script from your package.json file.');
  }
}
