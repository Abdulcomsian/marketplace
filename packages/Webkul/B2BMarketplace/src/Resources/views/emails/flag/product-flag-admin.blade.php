@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>

    <div style="padding: 30px;">

        <div  style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
            {{ __('shop::app.mail.customer.new.dear', ['customer_name' => $name]) }},

        </div>

        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                @if($supplier == Null)

                    {!! __('b2b_marketplace::app.mail.shop.supplier.report-product.info', [
                        'product_name' => '<a href="' . route('admin.catalog.products.edit', $product->id) . '" style="color: #0041FF; font-weight: bold;">' . $product->product->name . '</a>'
                        ,'reason'=>$data['reason'],'name' => $data['name']])
                    !!}
                @else
                    {!! __('b2b_marketplace::app.mail.shop.supplier.report-product-toadmin.info', [
                        'product_name' => '<a href="' . route('admin.catalog.products.edit', $product->id) . '" style="color: #0041FF; font-weight: bold;">' . $product->product->name . '</a>'
                        ,'reason'=>$data['reason'],'name' => $data['name']])
                    !!}
                @endif

            </p>

            <br/>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('b2b_marketplace::app.mail.shop.supplier.report-product-toadmin.thanks') }}
            </p>

        </div>
    </div>
@endcomponent