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

use App\Markdown\Highlighter\Renderers\FencedCodeRenderer;
use App\Markdown\Highlighter\Renderers\IndentedCodeRenderer;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\ExtensionInterface;

class HighlightCodeExtension implements ExtensionInterface
{
  public function register(EnvironmentBuilderInterface $environment): void
  {
    $themes = config('doczilla.themes');

    $shikiji = new Shikiji($themes);

    $codeBlockHighlighter = new ShikijiHighlighter($shikiji);

    $environment
        ->addRenderer(FencedCode::class, new FencedCodeRenderer($codeBlockHighlighter), 10)
        ->addRenderer(IndentedCode::class, new IndentedCodeRenderer($codeBlockHighlighter), 10);
  }
}
