<?php

namespace Plisio\PlisioPayment\Providers;

use Plisio\PlisioPayment\Services\Gateways\PlisioPaymentService;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Models\Payment;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, function (?string $html, array $data) {
            return $html . view('plugins/plisio::methods', $data)->render();
        }, 1, 2);

        add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, function (array $data, Request $request) {
            if ($data['type'] !== PLISIO_PAYMENT_METHOD_NAME) {
                return $data;
            }

            $paymentService = app(PlisioPaymentService::class);
            $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

            $result = $paymentService->makePayment($paymentData);

            if ($result) {
                $data['checkoutUrl'] = $result;
            } else {
                $data['error'] = true;
                $data['message'] = 'An error occurred while creating an order';
            }

            return $data;
        }, 1, 2);

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, function (?string $settings) {
            return $settings . view('plugins/plisio::settings')->render();
        }, 1);

        add_filter(BASE_FILTER_ENUM_ARRAY, function (array $values, string $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['PLISIO'] = PLISIO_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 1, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function (string $value, string $class) {
            if ($class == PaymentMethodEnum::class && $value == PLISIO_PAYMENT_METHOD_NAME) {
                $value = 'Plisio';
            }

            return $value;
        }, 1, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function (string $value, string $class) {
            if ($class == PaymentMethodEnum::class && $value == PLISIO_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )->toHtml();
            }

            return $value;
        }, 1, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == PLISIO_PAYMENT_METHOD_NAME) {
                $data = PlisioPaymentService::class;
            }

            return $data;
        }, 1, 2);
    }
}
