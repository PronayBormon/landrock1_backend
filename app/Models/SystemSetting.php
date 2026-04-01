<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_tagline',
        'logo',
        'dark_logo',
        'favicon',
        'contact_email',
        'support_email',
        'phone',
        'phone_alt',
        'address',
        'city',
        'state',
        'country',
        'postal_code',

        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'tiktok',

        'primary_color',
        'secondary_color',
        'currency',
        'currency_symbol',
        'timezone',
        'date_format',

        'maintenance_mode',
        'allow_registration',
        'email_verification',
        'sms_verification',

        'meta_title',
        'meta_description',
        'meta_keywords',

        'footer_text',
    ];
}
