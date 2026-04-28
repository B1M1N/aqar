<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private array $defaults = [
        'app_name'                 => 'عقاري',
        'auto_invoices'            => true,
        'late_invoice_updates'     => true,
        'rent_reminders'           => true,
        'lease_expiry_notify'      => true,
        'monthly_reports'          => true,
        'ai_predictions'           => true,
        'rent_reminder_days'       => 5,
        'lease_expiry_days'        => 30,
        'moyasar_enabled'          => false,
        'moyasar_secret_key'       => '',
        'moyasar_publishable_key'  => '',
    ];

    public function index(): View
    {
        $this->authorize('settings.view');

        $settings = [];
        foreach ($this->defaults as $key => $default) {
            $settings[$key] = config("aqari.{$key}", $default);
        }

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('settings.edit');

        $data = $request->validate([
            'app_name'                => ['required', 'string', 'max:60'],
            'rent_reminder_days'      => ['required', 'integer', 'min:1', 'max:30'],
            'lease_expiry_days'       => ['required', 'integer', 'min:1', 'max:90'],
            'moyasar_secret_key'      => ['nullable', 'string'],
            'moyasar_publishable_key' => ['nullable', 'string'],
        ]);

        $booleans = ['auto_invoices','late_invoice_updates','rent_reminders','lease_expiry_notify','monthly_reports','ai_predictions','moyasar_enabled'];
        foreach ($booleans as $key) {
            $data[$key] = $request->boolean($key);
        }

        // Persist to .env-style config override file
        $this->writeSettings($data);

        Artisan::call('config:clear');

        return back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }

    private function writeSettings(array $data): void
    {
        $path    = config_path('aqari.php');
        $content = "<?php\n\nreturn [\n";
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $content .= "    '{$key}' => " . ($value ? 'true' : 'false') . ",\n";
            } elseif (is_int($value)) {
                $content .= "    '{$key}' => {$value},\n";
            } else {
                $escaped  = addslashes($value);
                $content .= "    '{$key}' => '{$escaped}',\n";
            }
        }
        $content .= "];\n";
        file_put_contents($path, $content);
    }
}
