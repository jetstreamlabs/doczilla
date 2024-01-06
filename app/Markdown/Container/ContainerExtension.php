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

namespace App\Markdown\Container;

use App\Markdown\Container\Node\CodeGroup;
use App\Markdown\Container\Node\Container;
use App\Markdown\Container\Node\Tab;
use App\Markdown\Container\Parser\CodeGroupParser;
use App\Markdown\Container\Parser\ContainerParser;
use App\Markdown\Container\Renderer\CodeGroupRenderer;
use App\Markdown\Container\Renderer\ContainerRenderer;
use App\Markdown\Highlighter\Renderers\FencedCodeRenderer;
use App\Markdown\Highlighter\Shikiji;
use App\Markdown\Highlighter\ShikijiHighlighter;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Event\DocumentRenderedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\HtmlBlock;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\CommonMark\Output\RenderedContent;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

final class ContainerExtension implements ConfigurableExtensionInterface
{
  public array $rendered = [];

  public function configureSchema(ConfigurationBuilderInterface $builder): void
  {
    $builder->addSchema('container',
      Expect::structure([
        'default_class_name' => Expect::string(),
      ])
    );
  }

  public function register(EnvironmentBuilderInterface $environment): void
  {
    $themes = config('doczilla.themes');
    $shikiji = new Shikiji($themes);
    $codeBlockHighlighter = new ShikijiHighlighter($shikiji);

    $environment->addBlockStartParser(CodeGroupParser::createBlockStartParser(), 11)
                ->addRenderer(CodeGroup::class, new CodeGroupRenderer())
                ->addRenderer(FencedCode::class, new FencedCodeRenderer($codeBlockHighlighter));

    $environment->addBlockStartParser(ContainerParser::createBlockStartParser(), 10)
                ->addRenderer(Container::class, new ContainerRenderer());

    $environment->addEventListener(
      DocumentParsedEvent::class, [$this, 'onDocumentParsed'], -10
    );

    $environment->addEventListener(
      DocumentRenderedEvent::class, [$this, 'onDocumentRendered'], 10
    );
  }

  public function onDocumentParsed(DocumentParsedEvent $event)
  {
    $converter = new CommonMarkConverter();

    foreach ($event->getDocument()->iterator() as $node) {
      if ($this->isListBlockNode($node) && $this->isContainerNode($node->parent())) {
        $markdown = $node->parent()->data->get('markdown');

        $id = Str::uuid()->toString();

        $replacement = new HtmlBlock(HtmlBlock::TYPE_6_BLOCK_ELEMENT);
        $replacement->setLiteral("[[replace:$id]]");
        $node->replaceWith($replacement);

        $this->rendered[$id] = $converter->convert($markdown)->getContent();
      }
    }
  }

  public function onDocumentRendered(DocumentRenderedEvent $event)
  {
    $content = $event->getOutput()->getContent();

    $search = [];
    $replace = [];

    foreach ($this->rendered as $id => $rendered) {
      $search[] = "<p>[[replace:$id]]</p>";
      $replace[] = $rendered;

      $search[] = "[[replace:$id]]";
      $replace[] = $rendered;
    }

    $content = Str::replace($search, $replace, $content);

    $event->replaceOutput(
      new RenderedContent($event->getOutput()->getDocument(), $content)
    );
  }

  private function isContainerNode($node)
  {
    return $node instanceof Container
        || $node instanceof Tab;
  }

  private function isListBlockNode($node)
  {
    return $node instanceof ListBlock;
  }
}
