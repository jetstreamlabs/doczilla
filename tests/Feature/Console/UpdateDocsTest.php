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
use Illuminate\Support\Facades\Process;

it('updates docs and dependencies', function () {
  Process::fake();

  Config::set('doczilla.update_dependencies', true);

  $this->artisan('docs:update')
    ->expectsOutput('Updating composer packages ... ')
    ->expectsOutput('Updating node packages ... ')
    ->expectsOutput('Caching documentation ... ')
    ->expectsOutput('Indexing documentation ... ')
    ->expectsOutput('Building application ... ')
    ->expectsOutput('Documents have been updated and your app has been rebuilt.')
    ->assertExitCode(0);

  Process::assertRan('composer update');
  Process::assertRan('npm run build');
  Process::assertRan('npm update');
  Process::assertRan('php artisan docs:latest');
  Process::assertRan('php artisan docs:cache');
  Process::assertRan('php artisan docs:index');
});

it('updates docs without dependencies', function () {
  Process::fake();

  Config::set('doczilla.update_dependencies', false);

  $this->artisan('docs:update')
    ->expectsOutput('Caching documentation ... ')
    ->expectsOutput('Indexing documentation ... ')
    ->expectsOutput('Building application ... ')
    ->expectsOutput('Documents have been updated and your app has been rebuilt.')
    ->assertExitCode(0);

  Process::assertDidntRun('composer update');
  Process::assertDidntRun('npm update');
  Process::assertRan('php artisan docs:latest');
  Process::assertRan('php artisan docs:cache');
  Process::assertRan('php artisan docs:index');
  Process::assertRan('npm run build');
});
