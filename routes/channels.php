<?php

use Illuminate\Support\Facades\Broadcast;
use Twopoint0\ReverbChat\Models\Chat;

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return Chat::where('id', $conversationId)
        ->whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->exists();
});