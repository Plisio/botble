@if ($payment)
    <div class="alert alert-success" role="alert">
        <p class="mb-2">{{ trans('plugins/payment::payment.payment_id') }}: <strong>{{ data_get($payment, 'id') }}</strong></p>
    </div>

    @include('plugins/payment::partials.view-payment-source')
@endif
