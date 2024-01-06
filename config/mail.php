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
   * DEFAULT MAILER
   *
   * This option controls the default mailer that is used to send any email
   * messages sent by your application. Alternative mailers may be setup
   * and used as needed; however, this mailer will be used by default.
   */
  'default' => env('MAIL_MAILER', 'smtp'),

  /**
   * MAILER CONFIGURATIONS
   *
   * Here you may configure all of the mailers used by your application plus
   * their respective settings. Several examples have been configured for
   * you and you are free to add your own as your application requires.
   *
   * Doczilla supports a variety of mail "transport" drivers to be used while
   * sending an e-mail. You will specify which one you are using for your
   * mailers below. You are free to add additional mailers as required.
   *
   * Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
   *            "postmark", "log", "array", "failover"
   */
  'mailers' => [
    'smtp' => [
      'transport' => 'smtp',
      'url' => env('MAIL_URL'),
      'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
      'port' => env('MAIL_PORT', 587),
      'encryption' => env('MAIL_ENCRYPTION', 'tls'),
      'username' => env('MAIL_USERNAME'),
      'password' => env('MAIL_PASSWORD'),
      'timeout' => null,
      'local_domain' => env('MAIL_EHLO_DOMAIN'),
    ],

    'ses' => [
      'transport' => 'ses',
    ],

    'mailgun' => [
      'transport' => 'mailgun',
      // 'client' => [
      //     'timeout' => 5,
      // ],
    ],

    'postmark' => [
      'transport' => 'postmark',
      // 'message_stream_id' => null,
      // 'client' => [
      //     'timeout' => 5,
      // ],
    ],

    'sendmail' => [
      'transport' => 'sendmail',
      'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
    ],

    'log' => [
      'transport' => 'log',
      'channel' => env('MAIL_LOG_CHANNEL'),
    ],

    'array' => [
      'transport' => 'array',
    ],

    'failover' => [
      'transport' => 'failover',
      'mailers' => [
        'smtp',
        'log',
      ],
    ],
  ],

  /**
   * GLOBAL "FROM" ADDRESS
   *
   * You may wish for all e-mails sent by your application to be sent from
   * the same address. Here, you may specify a name and address that is
   * used globally for all e-mails that are sent by your application.
   */
  'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
    'name' => env('MAIL_FROM_NAME', 'Example'),
  ],

  /**
   * MARKDOWN MAIL SETTINGS
   *
   * If you are using Markdown based email rendering, you may configure your
   * theme and component paths here, allowing you to customize the design
   * of the emails. Or, you may simply stick with the Doczilla defaults!
   */
  'markdown' => [
    'theme' => 'default',

    'paths' => [
      resource_path('views/vendor/mail'),
    ],
  ],
];
