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

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
  /**
   * Creates the application.
   */
  public function createApplication(): Application
  {
    $app = require __DIR__.'/../bootstrap/app.php';

    $app->make(Kernel::class)->bootstrap();

    return $app;
  }
}
