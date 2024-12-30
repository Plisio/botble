<?php

namespace Plisio\PlisioPayment\Http\Controllers;

use Plisio\PlisioPayment\Services\Gateways\PlisioPaymentService;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Payment\Supports\PaymentHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class PlisioController extends Controller
{
    public function success(
        BaseHttpResponse $response,
    ): BaseHttpResponse {

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL())
            ->setMessage(__('Checkout successfully!'));
    }

    public function error(BaseHttpResponse $response): BaseHttpResponse
    {
        return $response
            ->setError()
            ->setNextUrl(PaymentHelper::getCancelURL())
            ->withInput()
            ->setMessage(__('Payment failed!'));
    }

    public function webhook(Request $request, PlisioPaymentService $plisioPaymentService): Response
    {
        $api_key = setting('payment_plisio_api_key');

        try {
            if (!$this->verifyCallbackData($_POST, $api_key)) {
                logger('Plisio response looks suspicious.');
                return response('Plisio response looks suspicious.');
            }

            $plisioOrderId = $request->get('order_number', null);
            $paymentStatus = $request->get('status');

            $payment = Payment::query()
                ->where('charge_id', $plisioOrderId)
                ->first();

            switch ($paymentStatus) {
                case 'completed':
                case 'mismatch':
                    $payment->status = PaymentStatusEnum::COMPLETED;
                    $payment->save();

                    break;
                case 'cancelled':
                case 'error':
                case 'expired':
                    $payment->status = PaymentStatusEnum::FAILED;
                    $payment->save();

                    break;
            }

            return response('Ok.');
        } catch (ModelNotFoundException) {
            return response('Not found.');
        } catch (Exception $exception) {
            report($exception);

            return response($exception->getMessage());
        }
    }

    private function verifyCallbackData($post, string $apiKey): bool
    {
        if (!isset($post['verify_hash'])) {
            return false;
        }

        $verifyHash = $post['verify_hash'];
        unset($post['verify_hash']);
        ksort($post);
        if (isset($post['expire_utc'])){
            $post['expire_utc'] = (string)$post['expire_utc'];
        }
        if (isset($post['tx_urls'])){
            $post['tx_urls'] = html_entity_decode($post['tx_urls']);
        }
        $postString = serialize($post);
        $checkKey = hash_hmac('sha1', $postString, $apiKey);
        if ($checkKey != $verifyHash) {
            return false;
        }

        return true;
    }
}
