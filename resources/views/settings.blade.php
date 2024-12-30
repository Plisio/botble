@php $plisioStatus = setting('payment_plisio_status'); @endphp
<div class="card mb-3">
<table class="table table-vcenter card-table">
    <tbody>
    <tr>
        <td class="border-end" style="width: 5%">
            <svg class="icon  svg-icon-ti-ti-wallet" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
                <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
            </svg>
        </td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/plisio/images/plisio.svg') }}"
                 alt="plisio">
        </td>
        <td>
            <a href="https://plisio.net" target="_blank">Plisio</a>
            <p>{{ 'Accept cryptocurrency with Plisio Payment Gateway' }}</p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div class="float-start" style="margin-top: 5px;">
                <div class="payment-name-label-group @if ($plisioStatus == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ setting('payment_plisio_name') }}</label>
                </div>
            </div>
            <div class="float-end">
                <a class="btn toggle-payment-item edit-payment-item-btn-trigger @if ($plisioStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn toggle-payment-item save-payment-item-btn-trigger @if ($plisioStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', PLISIO_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'Plisio']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'Plisio']) }}
                                :</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <p>
                                        <a href="https://plisio.net/" target="_blank">
                                            {{ trans('plugins/payment::payment.service_registration', ['name' => 'Plisio']) }}
                                        </a>
                                    </p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ 'Enter your secret key in a Plisio Secret Key field' }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="plisio_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input input-name" name="payment_plisio_name"
                                   id="plisio_name" data-counter="400"
                                   value="{{ setting('payment_plisio_name', trans('plugins/payment::payment.pay_online_via', ['name' => 'Plisio'])) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="payment_plisio_description">{{ trans('core/base::forms.description') }}</label>
                            <textarea class="next-input" name="payment_plisio_description"
                                      id="payment_plisio_description">{{ get_payment_setting('description', 'plisio', __('Payment with Plisio')) }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="payment_plisio_api_key">{{ 'Plisio Secret Key' }}</label>
                            <input type="text" class="next-input" name="payment_plisio_api_key"
                                   id="payment_plisio_api_key"
                                   value="{{ setting('payment_plisio_api_key') }}">
                        </div>

                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, 'plisio') !!}
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-end">
                <button class="btn btn-warning disable-payment-item @if ($plisioStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($plisioStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($plisioStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
</div>
