<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{

    protected $fillable = [
        'service',
        'key_name',
        'key_value',
        'environment',
        'is_active',
    ];

    /**
     * Encrypt value before saving
     */
    public function setKeyValueAttribute($value)
    {
        $this->attributes['key_value'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt value when reading
     */
    public function getKeyValueAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
