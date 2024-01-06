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
   * CROSS-ORIGIN RESOURCE SHARING (CORS) CONFIGURATION
   *
   * Here you may configure your settings for cross-origin resource sharing
   * or "CORS". This determines what cross-origin operations may execute
   * in web browsers. You are free to adjust these settings as needed.
   *
   * To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
   */
  'paths' => ['api/*', 'sanctum/csrf-cookie'],
  'allowed_methods' => ['*'],
  'allowed_origins' => ['*'],
  'allowed_origins_patterns' => [],
  'allowed_headers' => ['*'],
  'exposed_headers' => [],
  'max_age' => 0,
  'supports_credentials' => false,
];
