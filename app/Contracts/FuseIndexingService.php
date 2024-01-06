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

interface FuseIndexingService
{
  /**
   * Generate fuse index files for each version.
   *
   * @return void
   */
  public function generate(): void;

  /**
   * Loop through all pages and get an array of all pages.
   *
   * @return array
   */
  public function buildPageStack(): array;

  /**
   * Remove bad items from html for search indexes.
   *
   * @param  string  $html
   * @return string
   */
  public function normalizeContent(string $html): string;
}
