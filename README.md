# PHPMailer transport for Laravel

## About

This package provides a simple way to use PHPMailer with Laravel to handle sending emails.

## Installation

You can install the package via composer:

```bash
composer require "madeinua/laravel-phpmailer"
```

or manually add the following to your `composer.json` file:

```json
"madeinua/browser-console": "master"
```

You also need to publish the service provider:

```bash
php artisan vendor:publish --provider="LaravelPHPMailer\PHPMailerServiceProvider" --tag="transporter-config"
```

or by adding the provider to the `config/app.php` file:

```php
'providers' => [
    ...
    // Illuminate\Mail\MailServiceProvider::class,
    LaravelPHPMailer\PHPMailerServiceProvider::class,
],
```

Then in the `config/mail.php`, under `mailers`, you need to add a new entry:

```php
'mailers' => [
    ...
    'phpmailer' => [
        'transport' => 'phpmailer'
    ]
],
```

## Usage

When the package is installed and configured, all emails will be sent using PHPMailer:

```php
Mail::to($request->user())
    ->cc($moreUsers)
    ->bcc($evenMoreUsers)
    ->send(new OrderShipped($order));
```