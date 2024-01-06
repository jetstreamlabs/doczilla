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

return [

  /**
   * DEFAULT HASH DRIVER
   *
   * This option controls the default hash driver that will be used to hash
   * passwords for your application. By default, the bcrypt algorithm is
   * used; however, you remain free to modify this option if you wish.
   *
   * Supported: "bcrypt", "argon", "argon2id"
   */
  'driver' => 'bcrypt',

  /**
   * BCRYPT OPTIONS
   *
   * Here you may specify the configuration options that should be used when
   * passwords are hashed using the Bcrypt algorithm. This will allow you
   * to control the amount of time it takes to hash the given password.
   */
  'bcrypt' => [
    'rounds' => env('BCRYPT_ROUNDS', 12),
    'verify' => true,
  ],

  /**
   * ARGON OPTIONS
   *
   * Here you may specify the configuration options that should be used when
   * passwords are hashed using the Argon algorithm. These will allow you
   * to control the amount of time it takes to hash the given password.
   */
  'argon' => [
    'memory' => 65536,
    'threads' => 1,
    'time' => 4,
    'verify' => true,
  ],
];
