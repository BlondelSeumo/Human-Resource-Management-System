<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

use App\Models\Company;
use App\Models\Setting;
use Illuminate\Support\Str;

if (!function_exists('user')) {

    /**
     * Return current logged in user
     */
    function user()
    {
        $user = \Illuminate\Support\Facades\Auth::guard('employees')->user();

        if (is_a($user, \App\Models\Employee::class)) {
            session('user', $user);

            return $user;
        }

        return null;

    }

}

if (!function_exists('employee')) {

    /**
     * Return current logged in user
     */
    function employee()
    {
        $user = \Illuminate\Support\Facades\Auth::guard('employee')->user();

        if (is_a($user, \App\Models\Employee::class)) {
            session('user', $user);

            return $user;
        }

        return null;

    }

}

if (!function_exists('company')) {
    function company()
    {

        if (session()->has('company')) {
            return session('company');
        }

        if (auth()->user()) {
            $companyId = auth()->user()->company_id;
            if (!is_null($companyId)) {
                $company = Company::find($companyId);
                session(['company' => $company]);
            }
            return session('company');
        }

        return false;
    }
}
if (!function_exists('global_settings')) {

    function global_settings()
    {
//        if (!session()->has('global_settings')) {
            session(['global_settings' => Setting::first()]);
//        }

        return session('global_settings');
    }
}

if (!function_exists('admin')) {

    /**
     * @return null
     */
    function admin()
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        if (is_a($admin, 'App\Models\Admin')) {
            session('admin', $admin);
            return $admin;
        }

        return null;

    }

}

if (!function_exists('asset_url')) {

    // @codingStandardsIgnoreLine
    function asset_url($path)
    {
        $path = 'uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;

    }

}

if (!function_exists('module_enabled')) {
    function module_enabled($moduleName)
    {
        return \Nwidart\Modules\Facades\Module::collections()->has($moduleName);
    }
}

if (!function_exists('getDomainSpecificUrl')) {
    function getDomainSpecificUrl($url, $company = false)
    {
        if (module_enabled('Subdomain')) {
            // If company specific

            if ($company) {
                $url = str_replace(request()->getHost(), $company->sub_domain, $url);
                $url = str_replace('www.', '', $url);
                // Replace https to http for sub-domain to
                return $url = str_replace('https', 'http', $url);
            }

            // If there is no company and url has login means
            // New superadmin is created
            return $url = str_replace('login', 'super-admin-login', $url);
        }

        return $url;
    }
}

if (!function_exists('get_domain')) {

    function get_domain($host = false)
    {
        if(!$host){
            $host = $_SERVER['SERVER_NAME'];
        }

        $myhost = strtolower(trim($host));
        $count = substr_count($myhost, '.');
        if ($count === 2) {
            if (strlen(explode('.', $myhost)[1]) > 3) $myhost = explode('.', $myhost, 2)[1];
        } else if ($count > 2) {
            $myhost = get_domain(explode('.', $myhost, 2)[1]);
        }
        return $myhost;
    }
}


if (!function_exists('hrm_plugins')) {

    function hrm_plugins()
    {

        if (!session()->has('hrm_plugins')) {
            $plugins = \Nwidart\Modules\Facades\Module::allEnabled();
            session(['hrm_plugins' => array_keys($plugins)]);
        }
        return session('hrm_plugins');
    }
}
