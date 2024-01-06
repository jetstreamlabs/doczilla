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

use App\Markdown\Container\Node\Container;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

class ContainerRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
  private ConfigurationInterface $config;

  private string $class_name = '';

  private string $title = '';

  public function render(Node $node, ChildNodeRendererInterface $childRenderer)
  {
    Container::assertInstanceOf($node);

    $this->class_name = $this->config->get('container/default_class_name') ?? '';

    $titleNode = $node->data->get('title', '');

    $variant = $node->data->get('class_name', '');
    if ('' !== $variant) {
      $this->class_name .= ' '.$variant;
    }

    if ($titleNode === '' && $variant !== '') {
      $this->title = strtoupper($variant);
    } else {
      $this->title = strtoupper($titleNode);
    }

    $attrs = $node->data->get('attributes');
    if ('' !== $this->class_name) {
      $attrs['class'] = $this->class_name;
    }

    if ($this->title) {
      $data = new HtmlElement('h4', [], $this->title).
        $childRenderer->renderNodes($node->children());
    } else {
      $data = $childRenderer->renderNodes($node->children());
    }

    return new HtmlElement('div', $attrs, $data);
  }

  public function setConfiguration(ConfigurationInterface $configuration): void
  {
    $this->config = $configuration;
  }
}
