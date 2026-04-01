<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $data = SystemSetting::firstOrCreate(
            ['id' => 1],
            [
                'site_name'           => 'My Application',
                'site_tagline'        => null,
                'logo'                => null,
                'dark_logo'           => null,
                'favicon'             => null,

                'contact_email'       => 'info@example.com',
                'support_email'       => null,
                'phone'               => '',
                'phone_alt'           => null,
                'address'             => '',
                'city'                => '',
                'state'               => null,
                'country'             => '',
                'postal_code'         => null,

                'facebook'            => null,
                'twitter'             => null,
                'instagram'           => null,
                'linkedin'            => null,
                'youtube'             => null,
                'tiktok'              => null,

                'primary_color'       => '#000000',
                'secondary_color'     => '#ffffff',
                'currency'            => 'USD',
                'currency_symbol'     => '$',
                'timezone'            => 'UTC',
                'date_format'         => 'Y-m-d',

                'maintenance_mode'    => false,
                'allow_registration'  => true,
                'email_verification'  => true,
                'sms_verification'    => false,

                'meta_title'          => null,
                'meta_description'    => null,
                'meta_keywords'       => null,

                'footer_text'         => null,
            ]
        );

        return view('backend.layouts.settings.system.index', compact('data'));
    }
    public function SystemUpdate(Request $request)
    {
        $settings = SystemSetting::firstOrFail();

        $validated = $request->validate([
            'site_name'          => 'required|string|max:255',
            'site_tagline'       => 'nullable|string|max:255',
            'contact_email'      => 'required|email',
            'support_email'      => 'nullable|email',
            'phone'              => 'required|string|max:50',
            'address'            => 'required|string',

            // chunk upload paths (STRING, not file)
            'logo'               => 'required|string',
            'dark_logo'          => 'required|string',

            // 'primary_color'      => 'nullable|string',
            // 'secondary_color'    => 'nullable|string',

            // 'maintenance_mode'   => 'nullable|boolean',
            // 'allow_registration' => 'nullable|boolean',
            // 'email_verification' => 'nullable|boolean',
        ]);

        /* Checkbox normalization */
        // $validated['maintenance_mode']   = $request->boolean('maintenance_mode');
        // $validated['allow_registration'] = $request->boolean('allow_registration');
        // $validated['email_verification'] = $request->boolean('email_verification');

        /* Preserve old logo if empty */
        if (empty($validated['logo'])) {
            unset($validated['logo']);
        }

        if (empty($validated['dark_logo'])) {
            unset($validated['dark_logo']);
        }

        $settings->update($validated);

        return redirect()
            ->back()
            ->with('t-success', 'System settings updated successfully.');
    }
}
