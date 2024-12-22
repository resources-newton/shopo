<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
class Timezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $setting = Setting::first();
        // config(['app.timezone' => $setting->timezone]);
        // date_default_timezone_set($setting->timezone);

        if ($setting && $setting->timezone) {
            // Set the application timezone if the setting exists
            config(['app.timezone' => $setting->timezone]);
            date_default_timezone_set($setting->timezone);
        } else {
            // Set a default timezone to avoid errors
            $defaultTimezone = config('app.timezone', 'UTC');
            date_default_timezone_set($defaultTimezone);
        }

        return $next($request);
    }
}
