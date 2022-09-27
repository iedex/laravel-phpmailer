<?php

namespace LaravelPHPMailer;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class PHPMailerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Mail::extend('phpmailer', static function () {
            return new PHPMailerTransporter(
                new \PHPMailer\PHPMailer\PHPMailer(true)
            );
        });
    }
}