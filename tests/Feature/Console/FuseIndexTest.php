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
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
  $this->fuseIndexingServiceMock = Mockery::mock(FuseIndexingService::class);
  $this->app->instance(FuseIndexingService::class, $this->fuseIndexingServiceMock);
});

it('can generate Fuse indexes for all docs versions', function () {
  $this->fuseIndexingServiceMock->shouldReceive('generate')->once();

  Artisan::call('docs:index');

  $output = Artisan::output();
  expect($output)->toContain('All documentation has been indexed for search.');
});
