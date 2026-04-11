<?php

namespace App\Repositories;

use App\Models\Subscriber;

class SubscriberRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create($data)
    {
        return Subscriber::create($data);
    }

    public function exists($email)
    {
        return Subscriber::where('email', $email)->exists();
    }
}
