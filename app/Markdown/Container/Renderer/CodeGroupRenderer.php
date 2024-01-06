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

namespace App\Markdown\Container\Renderer;

use App\Markdown\Container\Node\CodeGroup;
use App\Markdown\Container\Node\Tab;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class CodeGroupRenderer implements NodeRendererInterface
{
  private string $id = '';

  private array $tabs = [];

  private string $class_name = 'code-group wrapper';

  public function render(Node $node, ChildNodeRendererInterface $childRenderer)
  {
    CodeGroup::assertInstanceOf($node);

    $this->id = $node->data->get('id', '');
    $this->tabs = $node->data->get('tabs');

    $tabs = $this->handleTabs($childRenderer);

    $attrs = $node->data->get('attributes');
    $attrs['class'] = $this->class_name;

    if ($this->id) {
      $attrs['id'] = $this->id;
    }

    return new HtmlElement('div', $attrs, $tabs);
  }

  protected function handleTabs($renderer)
  {
    $buttons = [];
    $content = [];
    foreach ($this->tabs as $tab) {
      Tab::assertInstanceOf($tab);

      $index = $tab->data->get('index');
      $name = $tab->data->get('name');
      $id = $tab->data->get('id', '');
      $active = $tab->data->get('isActive', false);

      $tabId = empty($id)
        ? 'tab-'.$this->make_id().'-'.$index
        : 'tab-'.$id.'-'.$index;

      $panelId = empty($id)
        ? 'panel-'.$this->make_id().'-'.$index
        : 'panel-'.$id.'-'.$index;

      $attrs = $tab->data->get('attributes');
      $attrs['class'] = 'tab-button';

      if ($active) {
        $attrs['class'] .= ' active';
      }

      $attrs['role'] = 'tab';
      $attrs['data-tab'] = "$index";
      $attrs['id'] = $tabId;

      $attrs['aria-controls'] = $panelId;

      if ($active) {
        $attrs['aria-selected'] = 'true';
      }

      $buttons[] = new HtmlElement('button', $attrs, htmlspecialchars($name));

      $attrs = $tab->data->get('attributes');
      $attrs['class'] = 'tab-content';

      if ($active) {
        $attrs['class'] .= ' active';
      }

      $attrs['id'] = $panelId;
      $attrs['data-index'] = "$index";
      $attrs['aria-labelledby'] = $tabId;

      if (! $active) {
        $attrs['aria-hidden'] = 'true';
      }

      $content[] = new HtmlElement('div', $attrs, $renderer->renderNodes($tab->children()));
    }

    $buttonAttrs = [
      'class' => 'tabs-header',
      'role' => 'tablist',
    ];

    return [
      new HtmlElement('div', $buttonAttrs, $buttons),
      new HtmlElement('div', ['class' => 'tabs-container'], $content),
    ];
  }

  private function make_id($length = 9)
  {
    return substr(md5(uniqid(rand(), true)), 0, $length);
  }
}
