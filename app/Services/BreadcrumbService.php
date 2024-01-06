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

namespace App\Services;

use App\Contracts\BreadcrumbService as BreadcrumbsContract;

class BreadcrumbService implements BreadcrumbsContract
{
  protected array $breadcrumbs = [];

  /**
   * Add a new breadcrumb to the stack.
   */
  public function add(string $text, string $route = null): BreadcrumbService
  {
    $this->breadcrumbs[] = [
      'text' => $text,
      'route' => ! is_null($route) ? $route : 'last',
    ];

    return $this;
  }

  /**
   * Return the breadcrumbs array.
   *
   * @return array
   */
  public function render(): array
  {
    return $this->breadcrumbs;
  }
}
