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

use Livewire\Volt\Volt;

it('redirects to the default version landing page', function () {
  $version = config('doczilla.versions.default');
  $page = config('doczilla.docs.landing');

  Volt::test('redirector')
    ->assertRedirect(route('docs.show', [$version, $page]));
});
