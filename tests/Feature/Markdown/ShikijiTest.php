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

use App\Markdown\Highlighter\Shikiji;
use Symfony\Component\Process\Exception\ProcessFailedException;

it('handles themes correctly and performs highlighting', function () {
  $code = "```php\n<?php echo 'Hello, World!';\n```";
  $language = 'php';
  $themes = ['nord', 'dracula'];

  $shikiji = new Shikiji();

  $highlightedCode = $shikiji->highlight($code, $language, $themes);

  expect($highlightedCode)->toContain('shiki shiki-themes');
});

it('handles the custom working path correctly', function () {
  $customPath = base_path('bin');
  Shikiji::setCustomWorkingDirPath($customPath);

  $shikiji = new Shikiji();

  $workingDirPath = $shikiji->getWorkingDirPath();
  expect($workingDirPath)->toBe($customPath);

  $code = "```php\n<?php echo 'Hello, World!';\n```";
  $language = 'php';

  $highlightedCode = $shikiji->highlightCode($code, $language);

  expect($highlightedCode)->toContain('shiki shiki-themes');

  Shikiji::setCustomWorkingDirPath(base_path('public'));

  $this->expectException(ProcessFailedException::class);

  $highlightedCode = $shikiji->highlightCode($code, $language);

  Shikiji::setCustomWorkingDirPath(null);
});
