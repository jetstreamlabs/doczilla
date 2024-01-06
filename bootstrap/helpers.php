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

use Illuminate\Support\Str;

if (! function_exists('copyright')) {
  function copyright()
  {
    return Str::of('&copy; ')
      ->append(date('Y').'. ')
      ->append(config('app.copyright').'. ')
      ->append('All Rights Reserved.');
  }
}

if (! function_exists('version')) {
  function version(): string
  {
    $file = base_path('version.json');

    if (file_exists($file)) {
      $data = json_decode(file_get_contents($file), true);

      return Str::of(config('app.name').' v')
      ->append($data['version']);
    }

    return config('app.name');
  }
}
