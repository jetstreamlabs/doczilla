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

namespace App\Contracts;

interface ContentCachingService
{
  /**
   * Cache all of our docs pages.
   *
   * @return void
   */
  public function make(): void;

  /**
   * Build an array of all current pages.
   *
   * @return array
   */
  public function getAllPages(): array;
}
