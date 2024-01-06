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

$app = new Illuminate\Foundation\Application(
  $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/**
 * BIND IMPORTANT INTERFACES
 *
 * Next, we need to bind some important interfaces into the container so
 * we will be able to resolve them when needed. The kernels serve the
 * incoming requests to this application from both the web and CLI.
 */
$app->singleton(
  Illuminate\Contracts\Http\Kernel::class,
  App\Kernel::class
);

$app->singleton(
  Illuminate\Contracts\Console\Kernel::class,
  App\Console\Kernel::class
);

$app->singleton(
  Illuminate\Contracts\Debug\ExceptionHandler::class,
  App\Exceptions\Handler::class
);

/**
 * RETURN THE APPLICATION
 *
 * This script returns the application instance. The instance is given to
 * the calling script so we can separate the building of the instances
 * from the actual running of the application and sending responses.
 */

return $app;
