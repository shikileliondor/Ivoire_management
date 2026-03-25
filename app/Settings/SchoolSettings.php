<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SchoolSettings extends Settings
{
    public string $name = '';

    public string $logo = '';

    public string $address = '';

    public string $phone = '';

    public string $email = '';

    public string $slogan = '';

    public string $director_name = '';

    public ?int $active_academic_year_id = null;

    public static function group(): string
    {
        return 'school';
    }
}
