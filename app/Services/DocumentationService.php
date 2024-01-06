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

use App\Contracts\DocumentationService as BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\ConverterInterface;
use Symfony\Component\DomCrawler\Crawler;

class DocumentationService implements BaseService
{
  public string $title = '';

  public string $description = '';

  public string $tags = '';

  public array $index = [];

  public array $toc = [];

  public string $version = '';

  public string $content = '';

  public ?array $prevPage = null;

  public ?array $nextPage = null;

  public string $canonical = '';

  public string $docsRoute = '';

  public string $sectionPage = '';

  public string $defaultVersion = '';

  public string $currentSection = '';

  public int $statusCode = 200;

  public array $publishedVersions = [];

  public string $defaultVersionUrl = '';

  /**
   * Create an new instance of the class.
   */
  public function __construct(
      protected ConverterInterface $converter
    ) {
    $this->docsRoute = route('docs.home');
    $this->defaultVersion = config('doczilla.versions.default');
    $this->publishedVersions = config('doczilla.versions.published');
    $this->defaultVersionUrl = route('docs.show', ['version' => $this->defaultVersion]);
  }

  /**
   * Service method for create action.
   *
   * @param  string  $version
   * @param  string|null  $page
   * @return Collection
   */
  public function show(string $version, string $page = null): Collection
  {
    if ($this->isNotPublishedVersion($version)) {
      $route = route('docs.show', [
        'version' => config('doczilla.versions.default'),
        'page' => config('doczilla.docs.landing'),
      ]);

      return collect([
        'route' => $route,
        'status' => 303,
      ]);
    }

    return $this->make($version, $page);
  }

  /**
   * Generate our requested documentation page.
   *
   * @param  string  $version
   * @param  string|null  $page
   * @param  array  $data
   * @return Collection
   */
  public function make(string $version, string $page = null, array $data = []): Collection
  {
    $this->version = $version;
    $this->sectionPage = $page ?: config('doczilla.docs.landing');

    $this->buildMenuIndex();

    $this->generatePreviousNext();

    $path = config('doczilla.docs.path')
      .DIRECTORY_SEPARATOR
      .$version
      .DIRECTORY_SEPARATOR
      .$page.'.md';

    if (File::exists($path)) {
      $this->currentSection = $this->sectionPage;

      $this->buildContent($path);

      $this->renderToc();

      $this->canonical = route('docs.show', [
        'version' => $this->version,
        'page' => $this->sectionPage,
      ]);

      return collect([
        'title' => $this->title,
        'description' => $this->description,
        'keywords' => $this->tags,
        'canonical' => $this->canonical,
        'toc' => $this->toc,
        'content' => $this->content,
        'sidebar' => $this->index,
        'versions' => $this->publishedVersions,
        'nextPage' => $this->nextPage,
        'prevPage' => $this->prevPage,
        'currentVersion' => $this->version,
        'currentSection' => $this->currentSection,
        'status' => $this->statusCode,
      ]);
    }

    $pathNotFound = config('doczilla.docs.path').'/404.md';
    $content = $this->converter->convert(File::get($pathNotFound));

    $this->title = 'Page Not Found';
    $this->description = '';
    $this->tags = '';
    $this->content = $content->getContent();
    $this->currentSection = '';
    $this->canonical = '';
    $this->statusCode = 404;

    return collect([
      'title' => $this->title,
      'description' => $this->description,
      'keywords' => $this->tags,
      'canonical' => $this->canonical,
      'toc' => $this->toc,
      'content' => $this->content,
      'sidebar' => $this->index,
      'versions' => $this->publishedVersions,
      'nextPage' => $this->nextPage,
      'prevPage' => $this->prevPage,
      'currentVersion' => $this->version,
      'currentSection' => $this->currentSection,
      'github' => config('doczilla.docs.github'),
      'twitter' => config('doczilla.docs.twitter'),
      'status' => $this->statusCode,
    ]);
  }

  /**
   * Public make function accessor.
   *
   * @param  string  $version
   * @param  string  $page
   * @return Collection
   */
  public function getPage(string $version, string $page): Collection
  {
    return $this->make($version, $page);
  }

  /**
   * Generate the menu.
   *
   * @return void
   */
  protected function buildMenuIndex(): void
  {
    $menu = config('doczilla.docs.path')
      .DIRECTORY_SEPARATOR
      .$this->version
      .DIRECTORY_SEPARATOR
      .'navigation.json';

    $this->index = File::json($menu);
  }

  /**
   * Build the content.
   *
   * @return void
   */
  protected function buildContent(string $path): void
  {
    $raw = $this->converter->convert(File::get($path));

    $fm = $raw->getFrontMatter();
    $body = $raw->getContent();

    $this->title = $fm['title'];
    $this->description = $fm['description'];
    $this->tags = ! empty($fm['tags']) ? implode(', ', $fm['tags']) : '';
    $this->content = $body;
  }

  /**
   * Generate previous and next links.
   *
   * @return void
   */
  protected function generatePreviousNext(): void
  {
    $links = [];

    foreach ($this->index as $category => $subLinks) {
      foreach ($subLinks as $set) {
        $links[] = [
          'text' => $set['title'],
          'link' => $set['uri'],
          'version' => $set['version'],
        ];
      }
    }

    collect($links)->filter(function ($link, $i) use ($links) {
      if ($link['link'] === $this->sectionPage && $i > 0) {
        $key = $i - 1;

        $prev = $links[$key];

        $this->prevPage = [
          'title' => $prev['text'],
          'link' => route('docs.show', ['version' => $prev['version'], 'page' => $prev['link']]),
        ];
      }

      if ($link['link'] === $this->sectionPage && $i < (count($links) - 1)) {
        $key = $i + 1;
        $next = $links[$key];

        $this->nextPage = [
          'title' => $next['text'],
          'link' => route('docs.show', ['version' => $next['version'], 'page' => $next['link']]),
        ];
      }
    });
  }

  /**
   * Generate TOC links from the toc in content.
   *
   * @return void
   */
  protected function renderToc(): void
  {
    $headings = (new Crawler($this->content, route('docs.home')))
      ->filter('h2, h3')
      ->extract(['_text']);

    $links = [];

    foreach ($headings as $heading) {
      $link = '#'.Str::of($heading)->lower()->slug();

      $links[] = [
        'text' => str_replace('#', '', $heading),
        'href' => $link,
      ];
    }

    $this->toc = $links;
  }

  /**
   * Check if the given version is in the published versions.
   *
   * @return bool
   */
  public function isPublishedVersion(string $version): bool
  {
    return in_array($version, $this->publishedVersions);
  }

  /**
   * Check if the given version is not in the published versions.
   *
   * @return bool
   */
  public function isNotPublishedVersion(string $version): bool
  {
    return ! $this->isPublishedVersion($version);
  }
}
