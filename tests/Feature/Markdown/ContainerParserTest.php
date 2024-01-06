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

it('parses class name and title in a container', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::: success Success Container\nContent\n:::";

  $html = $converter->convert($markdown)->getContent();

  expect($html)->toContain('<div class="custom-block not-prose success"><h4>SUCCESS CONTAINER</h4><p>Content</p></div>');
});

it('returns BlockStart::none for non-matching cursor', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::~ info \nThis is some container text.\n:::";
  $html = $converter->convert($markdown)->getContent();

  expect($html)->not->toContain('<div class="custom-block not-prose info">');
});

it('recognizes the end of the container block', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "::: info \nContent\n:::";

  $html = $converter->convert($markdown)->getContent();

  expect($html)->toContain('<div class="custom-block not-prose info"><h4>INFO</h4><p>Content</p></div>'); // Check for the proper closing of the container
});

it('replaces list blocks within containers with placeholders', function () {
  $converter = app(ConverterInterface::class);

  $markdown = ':::info my-container
- Item 1
- Item 2
:::';

  $html = $converter->convert($markdown)->getContent();

  expect($html)->toContain('<ul>');
  expect($html)->toContain('<li>Item 1</li>');
  expect($html)->toContain('<li>Item 2</li>');
});
