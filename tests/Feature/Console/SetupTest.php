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

use Illuminate\Support\Facades\Process;

it('executes necessary commands in non-testing environment', function () {
  Process::fake();

  $this->app->detectEnvironment(fn () => 'local');

  $this->artisan('docs:setup')->assertExitCode(0);

  Process::assertRan('npm install');
  Process::assertRan('npm run build');
});

it('bypasses certain artisan commands in the testing environment', function () {
  Process::fake();

  $this->app->detectEnvironment(fn () => 'testing');

  $this->artisan('docs:setup')->assertExitCode(0);

  Process::assertRan('npm install');
  Process::assertRan('npm run build');

  Process::assertDidntRun('php artisan docs:latest');
  Process::assertDidntRun('php artisan docs:cache');
  Process::assertDidntRun('php artisan docs:index');
});
