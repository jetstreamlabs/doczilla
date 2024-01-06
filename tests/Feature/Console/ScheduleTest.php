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

use Illuminate\Console\Scheduling\Schedule;

it('correctly manages scheduled commands', function () {
  $schedule = new Schedule;

  $kernel = app()->make(\App\Console\Kernel::class);
  $reflectedKernel = new ReflectionClass($kernel);
  $scheduleMethod = $reflectedKernel->getMethod('schedule');
  $scheduleMethod->setAccessible(true);

  $scheduleMethod->invoke($kernel, $schedule);

  $docsIndexEvent = collect($schedule->events())->first(function ($event) {
    return str_contains($event->command, 'docs:index');
  });

  expect($docsIndexEvent)->not->toBeNull();
  expect($docsIndexEvent->expression)->toBe('0 1 * * *');
});
