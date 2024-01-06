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

use App\Livewire\FusePalette;
use Illuminate\Support\Facades\File;
use function Pest\Livewire\livewire;

it('loads index file on mount', function () {
  $indexFileContent = json_encode(['title' => 'Test Title', 'content' => 'Test Content']);
  $versions = config('doczilla.versions.published');
  $indexFilePath = config('doczilla.docs.path').'/'.end($versions).'/index.json';

  File::shouldReceive('exists')
      ->with($indexFilePath)
      ->andReturn(true);

  File::shouldReceive('get')
      ->with($indexFilePath)
      ->andReturn($indexFileContent);

  livewire(FusePalette::class, ['version' => end($versions)])
      ->assertSet('currentIndex', json_decode($indexFileContent, true))
      ->assertSet('hasIndex', true);
});

it('handles query updates', function () {
  $currentIndex = [
    ['title' => 'Test Title 1', 'content' => 'Test Content 1'],
    ['title' => 'Test Title 2', 'content' => 'Test Content 2'],
  ];

  $versions = config('doczilla.versions.published');

  livewire(FusePalette::class, ['version' => end($versions)])
      ->set('currentIndex', $currentIndex)
      ->set('query', 'Test Title 1')
      ->assertSet('query', 'Test Title 1')
      ->assertSet('focusedItemIndex', -1);
});

it('opens the palette', function () {
  $versions = config('doczilla.versions.published');

  livewire(FusePalette::class, ['version' => end($versions)])
      ->call('openPalette')
      ->assertSet('show', true);
});

it('resets search when show is updated to false', function () {
  $versions = config('doczilla.versions.published');

  livewire(FusePalette::class, ['version' => end($versions)])
      ->set('show', true)
      ->set('query', 'Test')
      ->set('show', false)
      ->assertSet('query', '')
      ->assertSet('filteredResults', [])
      ->assertSet('focusedItemIndex', 0);
});

it('performs an action based on selected index', function () {
  $versions = config('doczilla.versions.published');

  $filteredResults = [
    [
      'item' => [
        'title' => "Godzilla's Roar",
        'content' => 'When it comes to Godzilla, the visuals are only half of the equation.',
        'route' => route('docs.show', ['version' => end($versions), 'page' => 'godzillas-roar']),
      ],
      'refIndex' => 4,
    ],
  ];

  livewire(FusePalette::class, ['version' => end($versions)])
      ->set('filteredResults', $filteredResults)
      ->call('performAction', 0)
      ->assertRedirect($filteredResults[0]['item']['route'])
      ->assertSet('query', '')
      ->assertSet('filteredResults', [])
      ->assertSet('focusedItemIndex', 0)
      ->assertSet('show', false);
});
