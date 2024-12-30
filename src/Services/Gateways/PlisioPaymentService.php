<?php

namespace Plisio\PlisioPayment\Services\Gateways;

use Botble\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Plisio\PlisioPayment\Lib\PlisioClient;
use Exception;
use Illuminate\Support\Arr;

class PlisioPaymentService
{
    public function makePayment(array $data): string|null
    {
        try {
            $chargeId = Str::upper(Str::random(10));
            $api_key = setting('payment_plisio_api_key');
            $client = new PlisioClient($api_key);

            $params = [
                'order_number' => $chargeId,
                'order_name' => "Order # $chargeId",
                'source_amount' => $data['amount'],
                'source_currency' => $data['currency'],
                'callback_url' => route('payments.plisio.webhook'),
                'success_url' => route('payments.plisio.success'),
                'cancel_url' => route('payments.plisio.error'),
                'email' => $data['address']['email'],
                'plugin' => 'Botble',
                'version' => '1.0.0'
            ];

            $order = $client->createTransaction($params);

            if ($order['status'] == 'error' || empty($order['data'])) {
                throw new Exception(implode(',', json_decode($order['data']['message'], true)));
            }
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());
            return null;
        }
        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'charge_id' => $chargeId,
            'order_id' => $data['order_id'],
            'customer_id' => $data['customer_id'],
            'customer_type' => $data['customer_type'],
            'payment_channel' => PLISIO_PAYMENT_METHOD_NAME,
            'status' => PaymentStatusEnum::PENDING,
        ]);

        return Arr::get($order, 'data.invoice_url');
    }
}
