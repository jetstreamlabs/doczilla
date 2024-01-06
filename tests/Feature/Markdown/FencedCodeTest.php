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

use App\Markdown\Highlighter\Renderers\FencedCodeRenderer;
use App\Markdown\Highlighter\Shikiji;
use App\Markdown\Highlighter\ShikijiHighlighter;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;

it('returns null for FencedCode block without info words', function () {
  $themes = config('doczilla.themes');
  $shikiji = new Shikiji($themes);
  $codeBlockHighlighter = new ShikijiHighlighter($shikiji);

  $renderer = new FencedCodeRenderer($codeBlockHighlighter);
  $fencedCode = new FencedCode(3, '`', 0);

  $language = $renderer->getSpecifiedLanguage($fencedCode);

  expect($language)->toBeNull();
});
