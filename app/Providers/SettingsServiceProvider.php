<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use TimurTurdyev\SimpleSettings\Facades\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->initSettings();
    }

    protected function initSettings(): void
    {
        if ($this->app->environment('testing')) {
            return;
        }

        try {
            $defaults = [
                'shop_name' => 'Магазин',
                'shop_email' => 'info@example.com',
                'shop_phone' => '+7 (999) 999-99-99',
                'shop_address' => '',
                'shop_logo' => '',
            ];

            foreach ($defaults as $key => $value) {
                if (!Setting::has($key)) {
                    Setting::set($key, $value);
                }
            }
        } catch (Exception $e) {
            //
        }
    }
}
