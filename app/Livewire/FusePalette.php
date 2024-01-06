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

namespace App\Livewire;

use Fuse\Fuse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class FusePalette extends Component
{
  public $currentIndex = [];

  public $filteredResults = [];

  public $query = '';

  public $show = false;

  public $focusedItemIndex = -1;

  public $hasIndex = false;

  public function mount($version)
  {
    $indexFile = Str::of(config('doczilla.docs.path'))
      ->append('/'.$version)
      ->append('/')
      ->append('index.json')
      ->toString();

    if (File::exists($indexFile)) {
      $this->hasIndex = true;
      $content = File::get($indexFile);
      $this->currentIndex = json_decode($content, true);
    }
  }

  public function updatedQuery($value)
  {
    $this->focusedItemIndex = -1;

    $fuse = new Fuse($this->currentIndex, [
      'keys' => ['title', 'content'],
    ]);

    $this->filteredResults = $fuse->search($value, [
      'limit' => 5,
    ]);
  }

  #[On('open-search-palette')]
  public function openPalette()
  {
    $this->show = true;
  }

  public function updatedShow($value)
  {
    if (! $value) {
      $this->resetSearch();
    }
  }

  public function performAction($index)
  {
    if (isset($this->filteredResults[$index])) {
      $item = $this->filteredResults[$index];
      $key = $item['refIndex'];

      $page = $this->currentIndex[$key];

      $this->resetSearch();
      $this->show = false;

      $this->redirect($page['route'], navigate: true);
    }
  }

  public function resetSearch()
  {
    $this->query = '';
    $this->filteredResults = [];
    $this->focusedItemIndex = 0;
  }

  public function render()
  {
    return view('livewire.fuse-palette');
  }
}
