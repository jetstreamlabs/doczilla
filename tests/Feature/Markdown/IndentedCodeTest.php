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

use App\Markdown\Highlighter\Renderers\IndentedCodeRenderer;
use App\Markdown\Highlighter\Shikiji;
use App\Markdown\Highlighter\ShikijiHighlighter;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

it('renders indented code with syntax highlighting', function () {
  $themes = config('doczilla.themes');
  $shikiji = new Shikiji($themes);
  $codeBlockHighlighter = new ShikijiHighlighter($shikiji);
  $renderer = new IndentedCodeRenderer($codeBlockHighlighter);

  $indentedCode = new IndentedCode();
  $indentedCode->setLiteral("    Sample Indented Code\n");

  $childRenderer = mock(ChildNodeRendererInterface::class);
  $childRenderer->shouldReceive('renderNodes')->andReturnUsing(function ($node) {
    return implode("\n", $node->getStrings());
  });

  $output = $renderer->render($indentedCode, $childRenderer);

  expect($output)->toContain('shiki shiki-themes');
});
