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

namespace App\Markdown\Container\Parser;

use App\Markdown\Container\Node\CodeGroup;
use App\Markdown\Container\Node\Tab;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;
use League\CommonMark\Util\ArrayCollection;
use League\CommonMark\Util\RegexHelper;

class CodeGroupParser extends AbstractBlockContinueParser
{
  private CodeGroup $block;

  private string $delim;

  private string $id;

  private array $tabs = [];

  private int $currentTabIndex = 0;

  private array $strings;

  public function __construct(string $id, string $delim)
  {
    $this->block = new CodeGroup();
    $this->strings = [];
    $this->id = $id;
    $this->delim = $delim;
  }

  public static function createBlockStartParser(): BlockStartParserInterface
  {
    return new class() implements BlockStartParserInterface
    {
      public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
      {
        if (':' !== $cursor->getNextNonSpaceCharacter()) {
          return BlockStart::none();
        }

        $match = $cursor->match('/^:{4}\s*code-group\b/');

        if (null === $match) {
          return BlockStart::none();
        }

        $line = $cursor->getLine();
        $remainder = substr($line, strlen($match));

        $id = '';

        if (preg_match('/^#(\w+)/', $remainder, $matches)) {
          $id = $matches[1];
        }

        $cursor->advanceToEnd();

        return BlockStart::of(new CodeGroupParser($id, $match))->at($cursor);
      }
    };
  }

  public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
  {
    $line = $cursor->getLine();

    // Check for the start of a new tab or the end of the code group
    if (preg_match('/^(@tab)(?::(active))?\s+(\w+)(?:#(\w+))?/', $line, $matches)) {
      // Finalize the current tab's content, if there is one
      if (! empty($this->tabs)) {
        $lastTabIndex = count($this->tabs) - 1;
        $node = $this->renderCodeBlocks();
        $this->tabs[$lastTabIndex]->appendChild($node);
      }

      $tab = new Tab();
      $tab->data->set('isActive', $matches[2] !== '' ? true : false);
      $tab->data->set('name', $matches[3] ?? '');
      $tab->data->set('id', isset($matches[4]) ? $matches[4] : '');
      $tab->data->set('index', $this->currentTabIndex);

      // Add the new tab to the list and increment the index
      $this->tabs[] = $tab;
      $this->currentTabIndex++;

      return BlockContinue::at($cursor);
    } elseif (preg_match('/^:{4}/', $line)) {
      // Finalize the last tab's content
      if (! empty($this->tabs)) {
        $lastTabIndex = count($this->tabs) - 1;
        $node = $this->renderCodeBlocks();
        $this->tabs[$lastTabIndex]->appendChild($node);
      }

      // Prepare to end the code group
      return BlockContinue::finished();
    } else {
      // Continue appending content to the current tab
      $this->strings[] = $line;
    }

    $cursor->advanceToNextNonSpaceOrTab();

    return BlockContinue::at($cursor);
  }

  private function renderCodeBlocks()
  {
    if (count($this->strings) > 0) {
      if (trim($this->strings[0]) === '') {
        array_shift($this->strings);
      }
      if (trim(end($this->strings)) === '') {
        array_pop($this->strings);
      }
    }

    $strings = new ArrayCollection(array_values($this->strings));

    $end = $strings->count() - 2;
    // Check if the first line indicates a start of a fenced code block
    if (preg_match('/^[ \t]*(?:`{3,}(?!.*`)|~{3,})/', $strings[0], $match)) {
      $fence = \ltrim($match[0], " \t");
      $fencedCodeBlock = new FencedCode(\strlen($fence), $fence[0], 0);
      $fencedCodeBlock->setInfo(RegexHelper::unescape(\trim($strings->first(), $fence)));
      $fencedCodeBlock->setLiteral(\implode("\n", $strings->slice(1, $end))."\n");

      $this->strings = [];

      return $fencedCodeBlock;
    }

    // Join all lines into a single string
    $content = implode("\n", $this->strings);

    $this->strings = [];

    return new Text($content);
  }

  public function closeBlock(): void
  {
    // Finalize the last tab if the block ends without a closing delimiter
    if (! empty($this->tabs)) {
      $lastTabIndex = count($this->tabs) - 1;
      $node = $this->renderCodeBlocks();
      $this->tabs[$lastTabIndex]->appendChild($node);
    }

    $this->block->data->set('id', $this->id);
    $this->block->data->set('tabs', $this->tabs);
  }

  public function getBlock(): AbstractBlock
  {
    return $this->block;
  }

  public function isContainer(): bool
  {
    return true;
  }

  public function canContain(AbstractBlock $childBlock): bool
  {
    return true;
  }
}
