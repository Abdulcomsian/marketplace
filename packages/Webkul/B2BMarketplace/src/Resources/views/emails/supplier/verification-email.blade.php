@component('b2b_marketplace::emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                @include ('shop::emails.layouts.logo')
            </a>
        </div>

        <div  style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
                {!! __('b2b_marketplace::app.mail.supplier.verification.heading') !!}
        </div>

        <div>
            {!! __('b2b_marketplace::app.mail.supplier.verification.summary') !!}
        </div>

        <div  style="margin-top: 40px; text-align: center">
            <a href="{{ route('b2b_marketplace.supplier.verify', $data['token']) }}" style="font-size: 16px;
            color: #FFFFFF; text-align: center; background: #0031F0; padding: 10px 100px;text-decoration: none;">
                {!! __('b2b_marketplace::app.mail.supplier.verification.verify') !!}
            </a>
        </div>
    </div>

@endcomponent