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

use Laravel\Sanctum\Sanctum;

return [

  /**
   * STATEFUL DOMAINS
   *
   * Requests from the following domains / hosts will receive stateful API
   * authentication cookies. Typically, these should include your local
   * and production domains which access your API via a frontend SPA.
   */
  'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    Sanctum::currentApplicationUrlWithPort()
  ))),

  /**
   * SANCTUM GUARDS
   *
   * This array contains the authentication guards that will be checked when
   * Sanctum is trying to authenticate a request. If none of these guards
   * are able to authenticate the request, Sanctum will use the bearer
   * token that's present on an incoming request for authentication.
   */
  'guard' => ['web'],

  /**
   * EXPIRATION MINUTES
   *
   * This value controls the number of minutes until an issued token will be
   * considered expired. This will override any values set in the token's
   * "expires_at" attribute, but first-party sessions are not affected.
   */
  'expiration' => null,

  /**
   * TOKEN PREFIX
   *
   * Sanctum can prefix new tokens in order to take advantage of numerous
   * security scanning initiatives maintained by open source platforms
   * that notify developers if they commit tokens into repositories.
   *
   * See: https://docs.github.com/en/code-security/secret-scanning/about-secret-scanning
   */
  'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

  /**
   * SANCTUM MIDDLEWARE
   *
   * When authenticating your first-party SPA with Sanctum you may need to
   * customize some of the middleware Sanctum uses while processing the
   * request. You may change the middleware listed below as required.
   */
  'middleware' => [
    'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
    'encrypt_cookies' => App\Middleware\EncryptCookies::class,
    'verify_csrf_token' => App\Middleware\VerifyCsrfToken::class,
  ],
];
