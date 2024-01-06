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

return [

  /**
   * VIEW STORAGE PATHS
   *
   * Most templating systems load templates from disk. Here you may specify
   * an array of paths that should be checked for your views. Of course
   * the usual Doczilla view path has already been registered for you.
   */
  'paths' => [
    resource_path('views'),
  ],

  /**
   * COMPILED VIEW PATH
   *
   * This option determines where all the compiled Blade templates will be
   * stored for your application. Typically, this is within the storage
   * directory. However, as usual, you are free to change this value.
   */
  'compiled' => env(
    'VIEW_COMPILED_PATH',
    realpath(storage_path('framework/views'))
  ),

];
