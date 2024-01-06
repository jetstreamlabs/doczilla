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

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class Shikiji
{
  protected array $defaultThemes;

  private static ?string $customWorkingDirPath = null;

  public function __construct(array $themes = [])
  {
    $this->defaultThemes = ! empty($themes)
      ? $themes
      : config('doczilla.themes');
  }

  public static function highlight(string $code, string $lang, ?array $themes = [])
  {
    return (new static())->highlightCode($code, $lang, $themes);
  }

  public function highlightCode(string $code, string $lang, ?array $themes = []): string
  {
    $themes = ! empty($themes)
      ? $themes :
      $this->defaultThemes;

    $themes = implode(':', $themes);

    return $this->callHighlighter($code, $lang, $themes);
  }

  public function getWorkingDirPath(): string
  {
    if (static::$customWorkingDirPath !== null && ($path = realpath(static::$customWorkingDirPath)) !== false) {
      return $path;
    }

    return realpath(base_path('bin'));
  }

  protected function callHighlighter(...$arguments): string
  {
    $command = [
      (new ExecutableFinder())->find('node', 'node', [
        '/usr/local/bin',
        '/opt/homebrew/bin',
      ]),
      'highlight.js',
      json_encode(array_values($arguments)),
    ];

    $process = new Process(
      $command,
      $this->getWorkingDirPath(),
      null,
    );

    $process->run();

    if (! $process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    return $process->getOutput();
  }

  public static function setCustomWorkingDirPath(?string $path)
  {
    static::$customWorkingDirPath = $path;
  }
}
