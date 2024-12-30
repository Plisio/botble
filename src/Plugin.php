<?php

namespace Plisio\PlisioPayment;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::query()
            ->whereIn('key', [
                'payment_plisio_name',
                'payment_plisio_description',
                'payment_plisio_status',
                'payment_plisio_api_key',
            ])->delete();
    }
}
