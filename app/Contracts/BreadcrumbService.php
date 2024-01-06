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

interface BreadcrumbService
{
  /**
   * Add a new breadcrumb to the stack.
   */
  public function add(string $text, string $route = null): BreadcrumbService;

  /**
   * Return the breadcrumbs array.
   */
  public function render(): array;
}
