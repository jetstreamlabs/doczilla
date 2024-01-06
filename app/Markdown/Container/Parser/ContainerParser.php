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

use App\Markdown\Container\Node\Container;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

class ContainerParser extends AbstractBlockContinueParser
{
  private Container $block;

  private string $delim;

  private string $class_name;

  private string $title;

  private array $rawMarkdownLines = [];

  private bool $isListDetected = false;

  public function __construct(string $class_name, string $title, string $delim)
  {
    $this->block = new Container();
    $this->delim = $delim;
    $this->class_name = $class_name;
    $this->title = $title;
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

        $match = $cursor->match('/^[\s\t]*:{3,}/u');

        if (null === $match) {
          return BlockStart::none();
        }

        $class_name = '';
        $title = '';

        if (false === $cursor->isAtEnd()) {
          $remainder = trim($cursor->getRemainder());
          $firstSpacePos = strpos($remainder, ' ');

          if ($firstSpacePos !== false) {
            $class_name = substr($remainder, 0, $firstSpacePos);
            $title = substr($remainder, $firstSpacePos + 1);
          } else {
            $class_name = $remainder;
            $title = '';
          }

          $cursor->advanceToEnd();
        }

        return BlockStart::of(new ContainerParser($class_name, $title, $match))->at($cursor);
      }
    };
  }

  public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
  {
    $this->captureListMarkdown($cursor);

    $cursor->advanceToNextNonSpaceOrTab();

    if ($this->delim === $cursor->getRemainder()) {
      return BlockContinue::finished();
    }

    return BlockContinue::at($cursor);
  }

  private function captureListMarkdown(Cursor $cursor)
  {
    if (! $this->isListDetected && $this->lineIndicatesList($cursor->getLine())) {
      $this->isListDetected = true;
    }

    if ($this->isListDetected) {
      if ($this->delim === $cursor->getRemainder()) {
        return;
      } else {
        $this->rawMarkdownLines[] = $cursor->getLine();
      }
    }
  }

  public function closeBlock(): void
  {
    if ($this->isListDetected) {
      $rawMarkdown = implode("\n", $this->rawMarkdownLines);
      $this->block->data->set('markdown', $rawMarkdown);
    }

    $this->block->data->set('class_name', $this->class_name);
    $this->block->data->set('title', $this->title);
  }

  private function lineIndicatesList(string $line): bool
  {
    return preg_match('/^(\-|\*|\+|\d+\.)\s/', $line);
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
