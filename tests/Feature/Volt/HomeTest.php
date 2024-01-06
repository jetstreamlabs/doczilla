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

it('renders the home page correctly', function () {
  Volt::test('home')
      ->assertSee('Doczilla')
      ->assertSeeHtml('<img id="logo" src="/storage/brand/splash.svg"');
});

it('renders correct links in the home page', function () {
  $version = config('doczilla.versions.default');
  $page = config('doczilla.docs.landing');

  $expectedDocLink = route('docs.show', [$version, $page]);
  $expectedGitHubLink = 'https://github.com/jetstreamlabs/doczilla';

  Volt::test('home')
      ->assertSee($expectedDocLink, false)
      ->assertSee($expectedGitHubLink, false)
      ->assertSee('Get Started')
      ->assertSee('View on GitHub');
});

it('displays dynamic content correctly in the home page', function () {
  Volt::test('home')
      ->assertSeeHtml(version())
      ->assertSee(version());
});
