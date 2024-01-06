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

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(
      \App\Contracts\BreadcrumbService::class,
      \App\Services\BreadcrumbService::class
    );

    $this->app->bind(
      \App\Contracts\DocumentationService::class,
      \App\Services\DocumentationService::class
    );

    $this->app->bind(
      \App\Contracts\ContentCachingService::class,
      \App\Services\ContentCachingService::class
    );

    $this->app->bind(
      \App\Contracts\FuseIndexingService::class,
      \App\Services\FuseIndexingService::class
    );

    $this->app->singleton('breadcrumbs', function (Application $app) {
      return $app->make(\App\Contracts\BreadcrumbService::class);
    });

    $this->app->singleton('docs', function (Application $app) {
      return $app->make(\App\Contracts\DocumentationService::class);
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Volt::mount([
      resource_path('views/livewire'),
      resource_path('views/pages'),
    ]);
  }
}
