<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Two\User as SocialiteGoogleUser;

class SocialAuthService
{
    public function resolveGoogleToken(Request $request): ?string
    {
        return $request->input('credential')
            ?? $request->input('id_token')
            ?? $request->input('access_token')
            ?? $request->input('token');
    }

    public function isGoogleIdToken(string $token): bool
    {
        return substr_count($token, '.') === 2;
    }

    public function getGoogleUserFromIdToken(string $idToken): ?SocialiteUser
    {
        $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken,
        ]);

        if (! $response->successful()) {
            return null;
        }

        $payload = $response->json();
        $clientId = config('services.google.client_id');

        if ($clientId && ($payload['aud'] ?? null) !== $clientId) {
            return null;
        }

        if (! filter_var($payload['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return null;
        }

        if (empty($payload['sub']) || empty($payload['email'])) {
            return null;
        }

        $user = new SocialiteGoogleUser();
        $user->map([
            'id' => $payload['sub'],
            'nickname' => null,
            'name' => $payload['name'] ?? null,
            'email' => $payload['email'],
            'avatar' => $payload['picture'] ?? null,
        ]);

        return $user;
    }

    public function findOrCreateFromGoogle(SocialiteUser $googleUser): User
    {
        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?? ($email ? Str::before($email, '@') : 'User');
        $avatar = $this->normalizeGoogleAvatarUrl($googleUser->getAvatar());

        $user = User::where('google_id', $googleId)->first()
            ?? ($email ? User::where('email', $email)->first() : null);

        if ($user) {
            $updates = [
                'google_id' => $googleId,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ];

            if (! $user->getRawOriginal('name') && $name) {
                $updates['name'] = $name;
            }

            if ($avatar) {
                $updates['avatar'] = $avatar;
            }

            $user->update($updates);

            return $user->fresh();
        }

        return User::create([
            'name' => $name,
            'email' => $email,
            'google_id' => $googleId,
            'password' => Str::random(32),
            'email_verified_at' => now(),
            'avatar' => $avatar,
        ]);
    }

    public function createAuthPayload(User $user): array
    {
        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
        ];
    }

    private function normalizeGoogleAvatarUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (str_starts_with($url, '//')) {
            $url = 'https:'.$url;
        }

        if (str_contains($url, 'googleusercontent.com')) {
            $url = preg_replace('/=s\d+-c$/', '=s256-c', $url) ?? $url;
            $url = preg_replace('/=s\d+$/', '=s256', $url) ?? $url;
        }

        return $url;
    }
}
