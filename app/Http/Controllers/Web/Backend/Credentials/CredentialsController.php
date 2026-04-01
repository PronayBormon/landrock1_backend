<?php

namespace App\Http\Controllers\Web\Backend\Credentials;

use App\Models\Credential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CredentialsController extends Controller
{
    /**
     * Edit credentials by service
     */
    public function edit(string $service)
    {
        $credentials = Credential::where('service', $service)
            ->where('is_active', 1)
            ->pluck('key_value', 'key_name')
            ->toArray();

        return view('backend.layouts.settings.smtp.index', compact('service', 'credentials'));
    }

    /**
     * Update credentials by service
     */
    public function update(Request $request, string $service)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            Credential::updateOrCreate(
                [
                    'service'     => $service,
                    'key_name'    => $key,
                    'environment' => 'production',
                ],
                [
                    'key_value' => $value,
                    'is_active' => true,
                ]
            );
        }

        return redirect()
            ->back()
            ->with('success', ucfirst($service) . ' credentials updated successfully.');
    }
}
