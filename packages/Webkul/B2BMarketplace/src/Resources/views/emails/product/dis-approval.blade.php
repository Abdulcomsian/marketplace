@component('shop::emails.layouts.master')

    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            <img src="{{ asset('themes/default/assets/images/logo.svg') }}">
        </a>
    </div>

    <div style="padding: 30px;">

        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('b2b_marketplace::app.mail.product.dear', ['name' => $supplierProduct->supplier->first_name . ' ' . $supplierProduct->supplier->last_name]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">

                {!! __('b2b_marketplace::app.mail.product.info-disapprove', ['name' => $supplierProduct->product->name]) !!}

            </p>
        </div>

        <div style="font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!!
                    __('shop::app.mail.order.help', [
                        'support_email' => '<a style="color:#0041FF" href="mailto:' . core()->getSenderEmailDetails()['email'] . '">' . core()->getSenderEmailDetails()['email']. '</a>'
                        ])
                !!}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.thanks') }}
            </p>
        </div>

    </div>

@endcomponent