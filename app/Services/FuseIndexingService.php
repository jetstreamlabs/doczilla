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

use App\Contracts\FuseIndexingService as FuseIndexingContract;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\ConverterInterface;

class FuseIndexingService implements FuseIndexingContract
{
  protected ?string $outputPath;

  public function __construct(
      protected ConverterInterface $converter,
      string $outputPath = null
    ) {
    $this->outputPath = $outputPath ?? config('doczilla.docs.path');
  }

  /**
   * Generate fuse index files for each version.
   *
   * @return void
   */
  public function generate(): void
  {
    $pages = $this->buildPageStack();

    // save to json index files
    collect($pages)->flatMap(function ($page, $version) {
      $path = Str::of($this->outputPath)
        ->append(DIRECTORY_SEPARATOR, $version)
        ->append(DIRECTORY_SEPARATOR, 'index.json');
      if (file_exists($path)) {
        unlink($path);
      }

      $content = json_encode($page);
      File::put($path, $content);
    });
  }

  /**
   * Loop through all pages and get an array of all pages.
   *
   * @return array
   */
  public function buildPageStack(): array
  {
    // get paths for docs versions
    $dirs = collect(File::directories(config('doczilla.docs.path')));

    $pages = $dirs->flatMap(function ($dir) {
      $file = File::get($dir.DIRECTORY_SEPARATOR.'navigation.json');
      $menus = collect(json_decode($file, true));

      return $menus->map(function ($values) {
        return collect($values)->map(function ($val) {
          $path = Str::of(config('doczilla.docs.path'))
              ->append(DIRECTORY_SEPARATOR, $val['version'])
              ->append(DIRECTORY_SEPARATOR, $val['uri'])
              ->append('.md');

          $file = File::get($path);
          $file = Str::replace("[[toc]]\n\n", '', $file);

          $file = $this->sanitizeBlocks($file);

          $raw = $this->converter->convert($file);

          return [
            'version' => $val['version'],
            'title' => $val['title'],
            'content' => $this->normalizeContent($raw->getContent()),
            'route' => route('docs.show', [$val['version'], $val['uri']]),
          ];
        });
      })->flatten(1)->groupBy('version')->toArray();
    })->all();

    return array_map(function ($version) {
      return array_map(function ($page) {
        return array_slice($page, 1);
      }, $version);
    }, $pages);
  }

  /**
   * Remove bad items from html for search indexes.
   *
   * @param  string  $html
   * @return string
   */
  public function normalizeContent(string $html): string
  {
    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);

    // Ensure HTML is UTF-8 and add a div tag to wrap your content
    $html = mb_convert_encoding('<div>'.$html.'</div>', 'HTML-ENTITIES', 'UTF-8');

    $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    for ($i = 1; $i <= 6; $i++) {
      // Get all tags for the current header level
      $headers = $dom->getElementsByTagName('h'.$i);

      // Loop through the headers and remove them
      while ($header = $headers->item(0)) {
        $header->parentNode->removeChild($header);
      }
    }

    // Remove <pre> tags
    $preTags = iterator_to_array($dom->getElementsByTagName('pre'));
    foreach ($preTags as $pre) {
      $pre->parentNode->removeChild($pre);
    }

    $buttons = iterator_to_array($dom->getElementsByTagName('button'));
    foreach ($buttons as $button) {
      $button->parentNode->removeChild($button);
    }

    // Remove div tags with specific x-ref attribute
    $divs = iterator_to_array($dom->getElementsByTagName('div'));
    foreach ($divs as $div) {
      if ($div->getAttribute('x-ref') === 'rawCode') {
        $div->parentNode->removeChild($div);
      }
    }

    $iframes = iterator_to_array($dom->getElementsByTagName('iframe'));
    foreach ($iframes as $iframe) {
      // Extract the src attribute and remove '?feature=oembed'
      $src = $iframe->getAttribute('src');
      $src = str_replace('?feature=oembed', '', $src);

      // Create a text node with the URL, adding spaces before and after
      $textNode = $dom->createTextNode(' '.$src.' ');

      // Replace the iframe with the text node
      $iframe->parentNode->replaceChild($textNode, $iframe);
    }

    // Remove carriage returns
    $output = preg_replace('/[\r\n]+/', '', $dom->saveHTML());

    // Remove added div wrapper
    $output = preg_replace('~<(/?(?:div))[^>]*>~i', '', $output);

    // Remove paragraph tags
    $output = preg_replace('~<(/?(?:p))[^>]*>~i', '', $output);

    // Remove <body> tags
    $output = preg_replace('/<\/?body[^>]*>/', '', $output);

    libxml_clear_errors();

    return $output;
  }

  /**
   * Remove block structures from indexes.
   *
   * @param  string  $md
   * @return string
   */
  protected function sanitizeBlocks(string $md): string
  {
    // Regular expression to match code-group blocks
    $patternCodeGroup = '/::: code-group(?:#\w+)?\s*(.*?):::/s';

    // Remove code-group blocks
    $sanitized = preg_replace($patternCodeGroup, '', $md);

    // Regular expression to match other markdown container blocks
    // It matches the entire block from opening ::: to the closing :::
    $patternOther = '/::: \w+ (.*?)\n(.*?)\n:::/s';

    // Replace the matched block with an empty string or format as needed
    $sanitized = preg_replace($patternOther, "#### $1\n\n$2", $sanitized);

    return $sanitized;
  }
}
