@if (setting('payment_plisio_status') == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_plisio" value="plisio" @if ((session('selected_payment_method') ?: setting('default_payment_method')) == PLISIO_PAYMENT_METHOD_NAME) checked @endif data-bs-toggle="collapse" data-bs-target=".payment_plisio_wrap" data-toggle="collapse" data-target=".payment_plisio_wrap" data-parent=".list_payment_method">
        <label for="payment_plisio" class="text-start">
            {{ setting('payment_plisio_name', 'Payment via cryptocurrency') }}
        </label>
        <div class="payment_plisio_wrap payment_collapse_wrap collapse @if ((session('selected_payment_method') ?: setting('default_payment_method')) == PLISIO_PAYMENT_METHOD_NAME) show @endif" style="padding: 15px 0;">
            <p>{!! BaseHelper::clean(get_payment_setting('description', PLISIO_PAYMENT_METHOD_NAME)) !!}</p>
        </div>
    </li>
@endif
