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

use App\Markdown\Container\Node\CodeGroup;
use App\Markdown\Container\Node\Tab;
use App\Markdown\Container\Renderer\CodeGroupRenderer;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

it('renders a CodeGroup with tabs correctly', function () {
  $renderer = new CodeGroupRenderer();

  $codeGroup = new CodeGroup();

  $tabs = [];

  for ($i = 0; $i < 2; $i++) {
    $tab = new Tab();
    $tab->data->set('name', "Tab $i");
    $tab->data->set('isActive', $i === 0);
    $tab->data->set('index', $i);

    if ($i % 2 === 0) {
      $tab->data->set('id', "tab-id-$i");
    }

    $paragraph = new Paragraph();
    $text = new Text("Content for Tab $i");
    $paragraph->appendChild($text);
    $tab->appendChild($paragraph);

    $tabs[] = $tab;
  }

  $codeGroup->data->set('tabs', $tabs);

  $childRenderer = mock(ChildNodeRendererInterface::class);
  $childRenderer
      ->shouldReceive('renderNodes')
      ->andReturnUsing(fn ($nodes) => implode("\n", array_map(fn ($node) => $node instanceof Paragraph ? $node->firstChild()->getLiteral() : '', $nodes)));

  $html = $renderer->render($codeGroup, $childRenderer)->__toString();

  expect($html)->toContain('<div class="code-group wrapper">');
  expect($html)->toContain('Tab 0');
  expect($html)->toContain('Tab 1');
  expect($html)->toContain('Content for Tab 0');
  expect($html)->toContain('Content for Tab 1');
  expect($html)->toContain('id="tab-');
  expect($html)->toContain('aria-controls="panel-');
  expect($html)->toContain('aria-selected="true"');
  expect($html)->toContain('<div class="tab-content');
  expect($html)->toContain('data-index="0"');
  expect($html)->toContain('aria-labelledby="tab-');
});
