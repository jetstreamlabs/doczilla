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
use App\Markdown\Highlighter\ShikijiHighlighter;

it('parses language and lines correctly from info line', function () {
  $shikiji = new Shikiji();
  $highlighter = new ShikijiHighlighter($shikiji);

  $infoLine = 'php {highlight: [1,2,3]}';

  $codeBlock = "<?php echo 'Hello, World!';";

  $highlightedCode = $highlighter->highlight($codeBlock, $infoLine);

  expect($highlightedCode)->toBeString();
});
