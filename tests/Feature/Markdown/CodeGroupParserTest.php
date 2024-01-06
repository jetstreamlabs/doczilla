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

use League\CommonMark\ConverterInterface;

it('parses code groups correctly', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::::code-group\n@tab Tab1\n```php\n<?php echo 'Hello, world!';\n```\n::::";
  $html = $converter->convert($markdown);

  expect($html->getContent())->toContain('<div class="code-group wrapper">'); // Adjust as per your expected HTML structure
});

it('returns BlockStart::none for non-matching cursor', function () {
  $converter = app(ConverterInterface::class);

  $markdown = ":::~~code-group\n@tab Tab1\n```php\n<?php echo 'Hello, world!';\n```\n::::";
  $html = $converter->convert($markdown);

  expect($html->getContent())->not->toContain('<div class="code-group wrapper">');
});

it('expects an id attribute for a code-group', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::::code-group#example\n@tab Tab1\n```php\n<?php echo 'Hello, world!';\n```\n::::";
  $html = $converter->convert($markdown);

  expect($html->getContent())->toContain('<div class="code-group wrapper" id="example">');
});

it('finalizes current tab content when a new tab starts', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::::code-group\n@tab Tab1\nContent for Tab1\n@tab Tab2\nContent for Tab2\n::::";
  $html = $converter->convert($markdown);

  expect($html->getContent())->toContain('Content for Tab1'); // Adjust this to match your expected HTML output
});

it('creates appropriate nodes based on tab content', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::::code-group\n@tab Tab1\n\n```php\n<?php echo 'Hello, world!';\n```\n\nSome text\n::::";
  $html = $converter->convert($markdown);

  expect($html->getContent())->toContain('class="relative language-php"');
  expect($html->getContent())->toContain('Hello, world!');
});

it('removes the last empty line in tab content', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::::code-group\n@tab Tab1\nContent for Tab1\n\n@tab Tab2\nContent for Tab2\n::::";
  $html = $converter->convert($markdown)->getContent();

  expect($html)->toContain('Content for Tab1');
});
