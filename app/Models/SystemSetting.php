<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getSetting($key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function setSetting($key, $value)
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function setRegistrationEnabled(bool $enabled)
    {
        SystemSetting::where('key', 'registration_enabled')->update(['value' => $enabled]);
    }

    public static function isRegistrationEnabled()
    {
        $registrationSetting = static::where('key', 'registration_enabled')->first();

        return $registrationSetting ? (bool) $registrationSetting->value : false;
    }

    public static function isLoginEnabled()
    {
        $loginSetting = static::where('key', 'login_enabled')->first();

        return $loginSetting ? (bool) $loginSetting->value : false;
    }
}
