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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class CheckoutLatestDocs extends Command
{
  protected $signature = 'docs:latest';

  protected $description = 'Pull down the latest versions of the docs.';

  public function handle()
  {
    $docsVersions = config('doczilla.versions.published');
    $repository = config('doczilla.versions.repo');

    foreach ($docsVersions as $version) {
      $path = config('doczilla.docs.path').DIRECTORY_SEPARATOR.$version;

      if (File::isDirectory($path)) {
        $this->info("Pulling latest updates for $version ...");
        $command = "cd $path && git fetch origin $version && git checkout $version && git reset --hard $version && git pull";
        Process::run($command);
      } else {
        $this->info("Cloning $version ...");
        $command = "git clone --single-branch --branch $version $repository $path";
        Process::run($command);
      }
    }

    $this->info('Documentation update process completed.');
  }
}
