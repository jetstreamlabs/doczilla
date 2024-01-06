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
   * DOCUMENTATION ROUTES
   *
   * This option provides an array of settings required by the DocZilla
   * documentation package. Modify them to fit your project documentation.
   */
  'docs' => [
    'route' => '/docs',
    'path' => resource_path('docs'),
    'landing' => 'getting-started',
    'github' => 'jetstreamlabs/doczilla', // Github package path
    'twitterx' => 'doczilla', // Twitter/X username
    'middleware' => ['web'],
  ],

  /**
   * UPDATE DEPENDENCIES
   *
   * Should the update command update your composer and node
   * dependencies when it runs?
   */
  'update_dependencies' => false,

  /**
   * Set your themes fo Shikiji code highlighting.
   */
  'themes' => [
    env('THEME_LITE', 'vitesse-light'), // Light theme first
    env('THEME_DARK', 'vitesse-dark'), // Dark theme second
  ],

  'markdown' => [
    'extensions' => [
      // League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension::class,
      // League\CommonMark\Extension\DescriptionList\DescriptionListExtension::class,
      League\CommonMark\Extension\ExternalLink\ExternalLinkExtension::class,
      // League\CommonMark\Extension\Footnote\FootnoteExtension::class,
      // League\CommonMark\Extension\Mention\MentionExtension::class,
      // League\CommonMark\Extension\SmartPunct\SmartPunctExtension::class,
    ],
    'renderer' => [
      'block_separator' => "\n",
      'inner_separator' => "\n",
      'soft_break' => "\n",
    ],
    'table_of_contents' => [
      'html_class' => 'lg:hidden toc',
      'position' => 'placeholder',
      'style' => 'bullet',
      'min_heading_level' => 2,
      'max_heading_level' => 3,
      'normalize' => 'relative',
      'placeholder' => '[[toc]]',
    ],
    'container' => [
      'default_class_name' => 'custom-block not-prose',
    ],
    'heading_permalink' => [
      'html_class' => 'header-anchor',
      'id_prefix' => '',
      'apply_id_to_heading' => false,
      'heading_class' => '',
      'fragment_prefix' => '',
      'insert' => 'before',
      'min_heading_level' => 1,
      'max_heading_level' => 6,
      'title' => '',
      'symbol' => '#',
      'aria_hidden' => true,
    ],
    'commonmark' => [
      'use_asterisk' => true,
      'use_underscore' => true,
      'enable_strong' => true,
      'enable_em' => true,
      'unordered_list_markers' => ['*', '+', '-'],
    ],
    'embed' => [
      'enabled' => true,
      'allowed_domains' => ['youtu.be', 'twitter.com', 'github.com'],
      'fallback' => 'link',
      'embed_adapter' => [
        'oembed:query_parameters' => [
          'maxwidth' => 800,
          'maxheight' => 380,
        ],
        'twitch:parent' => 'example.com',
        'facebook:token' => '1234|5678',
        'instagram:token' => '1234|5678',
        'twitter:token' => 'asdf',
      ],
    ],

    'html_input' => 'escape',
    'allow_unsafe_links' => true,
    'max_nesting_level' => PHP_INT_MAX,
    'slug_normalizer' => [
      'max_length' => 255,
      'unique' => 'document',
    ],
  ],

  /**
   * DOCUMENTATION VERSIONS
   *
   * Provide the published and default versions of your docs for proper
   * routing and UI implementation.
   */
  'versions' => [
    'default' => '2.0',
    'published' => [
      '2.0',
      '1.0',
    ],
    'repo' => 'git@github.com:jetstreamlabs/doczilla-demo-docs.git',
  ],

  /**
   * DOCUMENTATION CACHE SETTINGS
   *
   * Turn the cache on or off, and set the cache time (minutes) for docs.
   */
  'cache' => [
    'enabled' => env('DOCS_CACHE', true),
    'period' => env('DOCS_CACHE_TIME', 108000), //108000 = 1 month
  ],

  /**
   * DOCUMENTATION SEARCH
   *
   * Here you can add configure the search functionality of your docs.
   * You can choose the default engine of your search from the list
   * However, you can also enable/disable the search's visibility
   *
   * Supported Search Engines: 'algolia', 'internal'
   */
  'search' => [
    'enabled' => false,
    'default' => 'algolia',
    'engines' => [
      'internal' => [
        'index' => ['h2', 'h3'],
      ],
      'algolia' => [
        'key' => '',
        'index' => '',
      ],
    ],
  ],
];
