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

it('enables and inspects the commonmark embed extension', function () {
  $converter = app(ConverterInterface::class);

  $markdown = "Check out this video:\n\n https://youtu.be/aqz-KE-bpKQ?si=Dx_fg0yxVtLD_zV8";

  $html = $converter->convert($markdown)->getContent();

  expect($converter)->toBeInstanceOf(ConverterInterface::class);
  expect($html)->toContain('<iframe');
});
