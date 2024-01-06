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

/**
 * WEB ROUTES
 *
 * Here is where you can register web routes for your application. These
 * routes are loaded by the RouteServiceProvider and all of them will
 * be assigned to the "web" middleware group. Make something great!
 */
Volt::route('/', 'home')->name('home');
Volt::route('/docs', 'redirector')->name('docs.home');

Volt::route('/docs/{version}/{page?}', 'documentation')
  ->where('page', '(.*)')
  ->name('docs.show');
