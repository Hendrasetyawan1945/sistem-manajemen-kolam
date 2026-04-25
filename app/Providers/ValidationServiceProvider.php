<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Custom validation messages in Indonesian
        Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]{10,15}$/', $value);
        });

        Validator::extend('time_format', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^([0-5]?[0-9]):([0-5]?[0-9])\.([0-9]{2})$/', $value);
        });

        Validator::extend('currency_format', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value >= 0;
        });

        // Replace default validation messages
        Validator::replacer('phone_number', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'Format :attribute tidak valid. Gunakan 10-15 digit angka.');
        });

        Validator::replacer('time_format', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'Format :attribute harus MM:SS.MS (contoh: 01:23.45).');
        });

        Validator::replacer('currency_format', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute harus berupa angka positif.');
        });
    }
}