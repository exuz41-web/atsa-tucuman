<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'logo_path',
        'favicon_path',
        'address',
        'phone',
        'whatsapp',
        'email',
        'schedule',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'tiktok_url',
        'institutional_text',
        'footer_text',
        'google_analytics_id',
        'mail_from_address',
        'mail_from_name',
    ];

    public static function current(): self
    {
        return self::firstOrCreate([], [
            'site_name' => 'ATSA Tucumán',
            'address' => 'Paraguay y Thames, San Miguel de Tucumán',
            'phone' => '0381 4331665',
            'whatsapp' => '543814331665',
            'email' => 'contacto@atsa.com',
            'schedule' => 'Lunes a Viernes 8:00 a 16:00 hs',
            'facebook_url' => 'https://www.facebook.com/ATSATucuman',
            'institutional_text' => 'Asociación de Trabajadores de la Sanidad Argentina, seccional Tucumán.',
        ]);
    }

    public static function siteName(): string
    {
        try {
            return self::current()->site_name ?: 'ATSA Tucumán';
        } catch (Throwable) {
            return 'ATSA Tucumán';
        }
    }

    public static function logoUrl(): string
    {
        try {
            $path = self::current()->logo_path;

            if ($path) {
                return '/storage/'.ltrim(str_replace('\\', '/', $path), '/');
            }
        } catch (Throwable) {
            //
        }

        return '/images/logo-atsa.png';
    }

    public static function faviconUrl(): string
    {
        try {
            $setting = self::current();
            $path = $setting->favicon_path ?: $setting->logo_path;

            if ($path) {
                return '/storage/'.ltrim(str_replace('\\', '/', $path), '/');
            }
        } catch (Throwable) {
            //
        }

        return '/images/logo-atsa.png';
    }

    public static function logoPublicPath(): string
    {
        try {
            $path = self::current()->logo_path;
            $storagePath = $path ? storage_path('app/public/'.$path) : null;

            if ($storagePath && is_file($storagePath)) {
                return $storagePath;
            }
        } catch (Throwable) {
            //
        }

        return public_path('images/logo-atsa.png');
    }
}
