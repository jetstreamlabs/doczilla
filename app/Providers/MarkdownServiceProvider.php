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

use App\Markdown\Container\ContainerExtension;
use App\Markdown\Highlighter\HighlightCodeExtension;
use Embed\Embed;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    $this->app->singleton('converter', function (Container $app) {
      $config = $app->config->get('doczilla.markdown');

      $options = Arr::except($config, ['extensions', 'embed']);

      if ($config['embed']['enabled']) {
        $lib = $app->make(Embed::class);
        $lib->setSettings($config['embed']['embed_adapter']);

        $embed = Arr::except($config['embed'], ['enabled', 'embed_adapter']);
        $embed['adapter'] = new OscaroteroEmbedAdapter($lib);

        $options['embed'] = $embed;
      }

      $environment = new Environment($options);

      foreach ($this->defaultExtensions() as $extension) {
        $environment->addExtension($app->make($extension));
      }

      foreach (Arr::get($config, 'extensions') as $extension) {
        $environment->addExtension($app->make($extension));
      }

      if (isset($options['embed'])) {
        $environment->addExtension($app->make(EmbedExtension::class));
      }

      return new MarkdownConverter($environment);
    });

    $this->app->alias('converter', ConverterInterface::class);
  }

  protected function defaultExtensions(): array
  {
    return [
      CommonMarkCoreExtension::class,
      AttributesExtension::class,
      ContainerExtension::class,
      FrontMatterExtension::class,
      GithubFlavoredMarkdownExtension::class,
      HeadingPermalinkExtension::class,
      HighlightCodeExtension::class,
      TableOfContentsExtension::class,
    ];
  }
}
