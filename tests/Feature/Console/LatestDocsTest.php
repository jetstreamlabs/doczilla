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

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('updates any existing docs versions from source', function () {
  Process::fake();
  $docsVersions = config('doczilla.versions.published');
  $repository = config('doczilla.versions.repo');

  $expectedCommands = [];

  foreach ($docsVersions as $version) {
    $path = config('doczilla.docs.path').DIRECTORY_SEPARATOR.$version;

    if (is_dir($path)) {
      File::shouldReceive('isDirectory')
          ->with($path)
          ->andReturn(true);

      $expectedCommands[] = "cd $path && git fetch origin $version && git checkout $version && git reset --hard $version && git pull";
    } else {
      File::shouldReceive('isDirectory')
          ->with($path)
          ->andReturn(false);

      $expectedCommands[] = "git clone --single-branch --branch $version $repository $path";
    }
  }

  $this->artisan('docs:latest')->assertExitCode(0);

  foreach ($expectedCommands as $command) {
    Process::assertRan($command);
  }

  $this->artisan('docs:latest')->expectsOutput('Documentation update process completed.');
});

it("pulls versions from source that aren't yet installed", function () {
  Process::fake();

  Config::set('doczilla.versions.published', ['3.0']);
  $repository = config('doczilla.versions.repo');
  $path = config('doczilla.docs.path').DIRECTORY_SEPARATOR.'3.0';

  File::shouldReceive('isDirectory')
    ->with($path)
    ->andReturn(false);

  $this->artisan('docs:latest')->assertExitCode(0);

  $command = "git clone --single-branch --branch 3.0 {$repository} {$path}";
  Process::assertRan($command);

  $this->artisan('docs:latest')->expectsOutput('Documentation update process completed.');
});
