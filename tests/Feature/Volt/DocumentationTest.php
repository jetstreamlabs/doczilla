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

it('initializes the documentation component correctly', function () {
  getDefault()
      ->assertViewHas('title')
      ->assertViewHas('canonical')
      ->assertViewHas('sidebar')
      ->assertViewHas('versions')
      ->assertViewHas('nextPage')
      ->assertViewHas('prevPage')
      ->assertViewHas('currentVersion')
      ->assertViewHas('currentSection')
      ->assertViewHas('status')
      ->assertViewHas('content')
      ->assertViewHas('breadcrumbs');
});

it('renders the 404 not found page', function () {
  Volt::test('documentation', ['version' => '1.0', 'page' => 'some-missing-page'])
    ->assertSet('status', 404)
    ->assertSet('title', 'Page Not Found');
});

it('sets the correct metadata for the docs landing page', function () {
  getDefault()
      ->assertSet('description', 'Welcome to Doczilla')
      ->assertSet('keywords', 'Doczilla, welcome')
      ->assertSet('canonical', route('docs.show', ['version' => '1.0', 'page' => 'getting-started']));
});

it('initializes the mobile menu correctly', function () {
  getDefault()
    ->assertSeeVolt('mobile');
});

it('initializes the sidebar menu correctly', function () {
  getDefault()
    ->assertSeeVolt('sidebar');
});

it('initializes the main navbar correctly', function () {
  getDefault()
    ->assertSeeVolt('navbar');
});

it('initializes the prev and next page navs correctly', function () {
  getDefault()
    ->assertSeeVolt('previous-next');
});

it('initializes the table of contents correctly', function () {
  getDefault()
    ->assertSeeVolt('toc');
});

it('initializes the search palette correctly', function () {
  getDefault()
    ->assertSeeVolt('fuse-palette');
});
