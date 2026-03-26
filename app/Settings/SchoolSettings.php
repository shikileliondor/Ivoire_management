<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SchoolSettings extends Settings
{
    public string  $name                  = '';
    public string  $short_name            = '';
    public string  $logo                  = '';
    public string  $address               = '';
    public string  $city                  = 'Abidjan';
    public string  $phone                 = '';
    public string  $email                 = '';
    public string  $slogan                = '';
    public string  $director_name         = '';
    public string  $director_signature    = '';
    public ?int    $current_academic_year_id = null;
    public string  $ministry_code         = '';
    public string  $district              = '';
    public int     $students_per_class_max = 45;
    public string  $school_level          = 'primaire';

    public static function group(): string
    {
        return 'school';
    }
}
