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

use Illuminate\Support\Collection;

interface DocumentationService
{
  /**
   * Service method for create action.
   *
   * @param  string  $version
   * @param  string|null  $page
   * @return Collection
   */
  public function show(string $version, string $page = null): Collection;

  /**
   * Generate our requested documentation page.
   *
   * @param  string  $version
   * @param  string|null  $page
   * @param  array  $data
   * @return Collection
   */
  public function make(string $version, string $page = null, array $data = []): Collection;

  /**
   * Public make function accessor.
   *
   * @param  string  $version
   * @param  string  $page
   * @return Collection
   */
  public function getPage(string $version, string $page): Collection;

  /**
   * Check if the given version is in the published versions.
   *
   * @return bool
   */
  public function isPublishedVersion(string $version): bool;

  /**
   * Check if the given version is not in the published versions.
   *
   * @return bool
   */
  public function isNotPublishedVersion(string $version): bool;
}
