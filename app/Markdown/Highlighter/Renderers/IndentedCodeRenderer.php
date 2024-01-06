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

namespace App\Markdown\Highlighter\Renderers;

use App\Markdown\Highlighter\ShikijiHighlighter;
use League\CommonMark\Extension\CommonMark\Renderer\Block\IndentedCodeRenderer as BaseIndentedCodeRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class IndentedCodeRenderer implements NodeRendererInterface
{
  protected ShikijiHighlighter $highlighter;

  protected BaseIndentedCodeRenderer $baseRenderer;

  public function __construct(ShikijiHighlighter $codeBlockHighlighter)
  {
    $this->highlighter = $codeBlockHighlighter;
    $this->baseRenderer = new BaseIndentedCodeRenderer();
  }

  public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
  {
    $element = $this->baseRenderer->render($node, $childRenderer);

    return $this->highlighter->highlight($element->getContents());
  }
}
