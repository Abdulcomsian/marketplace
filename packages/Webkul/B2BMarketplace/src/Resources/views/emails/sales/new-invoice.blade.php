@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            <img src="{{ asset('themes/default/assets/images/logo.svg') }}">
        </a>
    </div>

    <?php $supplierOrder = $supplierInvoice->order; ?>

    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <span style="font-weight: bold;">
                {{ __('shop::app.mail.invoice.heading', ['order_id' => $supplierOrder->order_id, 'invoice_id' => $supplierInvoice->invoice_id]) }}
            </span> <br>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.dear', ['customer_name' => $supplierOrder->order->customer_full_name]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! __('b2b_marketplace::app.mail.sales.invoice.greeting', [
                    'order_id' => '<a href="' . route('customer.orders.view', $supplierOrder->order_id) . '" style="color: #0041FF; font-weight: bold;">#' . $supplierOrder->order_id . '</a>',
                    'created_at' => $supplierOrder->created_at
                    ])
                !!}
            </p>
        </div>

        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            {{ __('shop::app.mail.invoice.summary') }}
        </div>

        <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
            <div style="line-height: 25px;">
                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.shipping-address') }}
                </div>

                <div>
                    {{ $supplierOrder->order->shipping_address->name }}
                </div>

                <div>
                    {{ $supplierOrder->order->shipping_address->address1 }}, {{ $supplierOrder->order->shipping_address->address2 ? $supplierOrder->order->shipping_address->address2 . ',' : '' }} {{ $supplierOrder->order->shipping_address->state }}
                </div>

                <div>
                    {{ core()->country_name($supplierOrder->order->shipping_address->country) }} {{ $supplierOrder->order->shipping_address->postcode }}
                </div>

                <div>---</div>

                <div style="margin-bottom: 40px;">
                    {{ __('shop::app.mail.order.contact') }} : {{ $supplierOrder->order->shipping_address->phone }}
                </div>

                <div style="font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.shipping') }}
                </div>

                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ $supplierOrder->order->shipping_title }}
                </div>
            </div>

            <div style="line-height: 25px;">
                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.billing-address') }}
                </div>

                <div>
                    {{ $supplierOrder->order->billing_address->name }}
                </div>

                <div>
                    {{ $supplierOrder->order->billing_address->address1 }}, {{ $supplierOrder->order->billing_address->address2 ? $supplierOrder->order->billing_address->address2 . ',' : '' }} {{ $supplierOrder->order->billing_address->state }}
                </div>

                <div>
                    {{ core()->country_name($supplierOrder->order->billing_address->country) }} {{ $supplierOrder->order->billing_address->postcode }}
                </div>

                <div>---</div>

                <div style="margin-bottom: 40px;">
                    {{ __('shop::app.mail.order.contact') }} : {{ $supplierOrder->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px; color: #242424;">
                    {{ __('shop::app.mail.order.payment') }}
                </div>

                <div style="font-weight: bold;font-size: 16px; color: #242424;">
                    {{ core()->getConfigData('sales.paymentmethods.' . $supplierOrder->order->payment->method . '.title') }}
                </div>
            </div>
        </div>

        <div style="width: 100%;overflow-x: auto;">
            <table style="width: 100%;border-collapse: collapse;text-align: left;">
                <thead>
                    <tr>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.price') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.qty') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                        @if ($supplierInvoice->discount_amount > 0)
                            <th>{{ __('shop::app.customer.account.order.view.discount-amount') }}</th>
                        @endif
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($supplierInvoice->items as $supplierInvoiceItem)
                        <?php $baseInvoiceItem = $supplierInvoiceItem->item; ?>
                        <tr>
                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ $baseInvoiceItem->name }}</td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">
                                {{ core()->formatPrice($baseInvoiceItem->price, $supplierOrder->order->order_currency_code) }}
                            </td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ $baseInvoiceItem->qty }}</td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">
                                {{ core()->formatPrice($baseInvoiceItem->total, $supplierOrder->order->order_currency_code) }}
                            </td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">
                                {{ core()->formatPrice($baseInvoiceItem->tax_amount, $supplierOrder->order->order_currency_code) }}
                            </td>

                            @if ($supplierInvoice->discount_amount > 0)
                                <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">
                                    {{ core()->formatPrice($baseInvoiceItem->discount_amount, $supplierOrder->order->order_currency_code) }}
                                </td>
                            @endif

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">
                                {{ core()->formatPrice($baseInvoiceItem->total + $baseInvoiceItem->tax_amount, $supplierOrder->order->order_currency_code) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="font-size: 16px;color: #242424;line-height: 30px;float: right;width: 40%;margin-top: 20px;">
            <div>
                <span>{{ __('shop::app.mail.order.subtotal') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($supplierInvoice->sub_total, $supplierInvoice->invoice->order_currency_code) }}
                </span>
            </div>

            <div>
                <span>{{ __('shop::app.mail.order.shipping-handling') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($supplierInvoice->shipping_amount, $supplierInvoice->invoice->order_currency_code) }}
                </span>
            </div>

            <div>
                <span>{{ __('shop::app.mail.order.tax') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($supplierInvoice->tax_amount, $supplierInvoice->invoice->order_currency_code) }}
                </span>
            </div>

            @if ($supplierInvoice->discount_amount)
                <div>
                    <span>{{ __('shop::app.customer.account.order.view.discount-amount') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($supplierInvoice->discount_amount, $supplierInvoice->invoice->order_currency_code) }}
                    </span>
                </div>
            @wndif

            <div style="font-weight: bold">
                <span>{{ __('shop::app.mail.order.grand-total') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($supplierInvoice->grand_total, $supplierInvoice->invoice->order_currency_code) }}
                </span>
            </div>
        </div>

        <div style="margin-top: 65px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block;width: 100%">
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
