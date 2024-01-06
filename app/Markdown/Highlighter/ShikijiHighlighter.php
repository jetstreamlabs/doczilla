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

namespace App\Markdown\Highlighter;

use Exception;

class ShikijiHighlighter
{
  public function __construct(
      protected Shikiji $shikiji
    ) {
  }

  public function highlight(string $codeBlock, ?string $infoLine = null): string
  {
    $codeBlockWithoutTags = strip_tags($codeBlock);
    $contents = htmlspecialchars_decode($codeBlockWithoutTags);
    $definition = $this->parseLangAndLines($infoLine);
    $language = $definition['lang'] ?? 'bash';

    try {
      return $this->shikiji->highlightCode($contents, $language);
    } catch (Exception) {
      $highlightedContents = $codeBlock;
    }

    return $highlightedContents;
  }

  protected function parseLangAndLines(?string $language): array
  {
    $parsed = [
      'lang' => $language,
    ];

    if ($language === null) {
      return $parsed;
    }

    $bracePosition = strpos($language, '{');

    if ($bracePosition === false) {
      return $parsed;
    }

    preg_match_all('/{([^}]*)}/', $language, $matches);

    $parsed['lang'] = substr($language, 0, $bracePosition);

    return $parsed;
  }
}
