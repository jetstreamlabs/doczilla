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
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Renderer\Block\FencedCodeRenderer as BaseFencedCodeRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

class FencedCodeRenderer implements NodeRendererInterface
{
  protected ShikijiHighlighter $highlighter;

  protected BaseFencedCodeRenderer $baseRenderer;

  public function __construct(ShikijiHighlighter $codeBlockHighlighter)
  {
    $this->highlighter = $codeBlockHighlighter;
    $this->baseRenderer = new BaseFencedCodeRenderer();
  }

  public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
  {
    $element = $this->baseRenderer->render($node, $childRenderer);
    $originalCode = $node->getLiteral();

    $highlighted = $this->highlighter->highlight(
      $element->getContents(), $this->getSpecifiedLanguage($node)
    );

    $copyButton = new HtmlElement(
      'button',
      [
        '@click' => '$clipboard($refs.rawCode.textContent)',
        'class' => 'copy',
      ],
      null
    );

    $rawCodeElement = new HtmlElement(
      'div',
      [
        'x-ref' => 'rawCode',
        'class' => 'hidden',
      ],
      $originalCode
    );

    $containerClass = 'relative language-';
    if (isset($node->getInfoWords()[0])) {
      $containerClass .= $node->getInfoWords()[0];
    }

    $containerContent = $copyButton.$highlighted.$rawCodeElement;

    $container = new HtmlElement(
      'div',
      ['class' => $containerClass, 'x-data' => '{}'],
      $containerContent
    );

    return (string) $container;
  }

  public function getSpecifiedLanguage(FencedCode $block): ?string
  {
    $infoWords = $block->getInfoWords();

    if (empty($infoWords) || empty($infoWords[0])) {
      return null;
    }

    return Xml::escape($infoWords[0]);
  }
}
