
@if (Auth::guard('customer')->check())
    <li>
        <a style="color:#242424" href="{{ route('b2b_marketplace.shop.customers.rfq.index') }}">
            <img class="logo" src="{{ asset('themes/b2b/assets/images/velocity-Icon-RFQ.svg') }}" alt="" style="margin-bottom: -5px;" height="24" width="24"/>
            {{ __('RFQ') }}
        </a>
    </li>
@else
    <li>
        <a style="color:#242424" href="{{ route('b2b_marketplace.supplier_central.index') }}">
            {{ __('b2b_marketplace::app.shop.supplier.layouts.sell') }}
        </a>
    </li>

    {!! view_render_event('b2b_marketplace.shop.layout.header.account-item.before') !!}

    <li>
        <span class="dropdown-toggle">
            <i class="icon account-icon"></i>

            <span class="name">{{ __('Supplier') }}</span>

        </span>

        @guest('customer')
            <ul class="dropdown-list account guest">
                <li>
                    <div>
                        <label style="color: #9e9e9e; font-weight: 700; text-transform: uppercase; font-size: 15px;">
                            {{ __('shop::app.header.title') }}
                        </label>
                    </div>

                    <div style="margin-top: 5px;">
                        <span style="font-size: 12px;">{{ __('Supplier Account') }}</span>
                    </div>

                    <div style="margin-top: 15px;">
                        <a class="btn btn-primary btn-md" href="{{ route('b2b_marketplace.shop.supplier.session.index') }}" style="color: #ffffff">
                            {{ __('shop::app.header.sign-in') }}
                        </a>

                        <a class="btn btn-primary btn-md" href="{{ route('b2b_marketplace.shop.suppliers.signup.index') }}" style="float: right; color: #ffffff">
                            {{ __('shop::app.header.sign-up') }}
                        </a>
                    </div>
                </li>
            </ul>
        @endguest
    </li>

    {!! view_render_event('b2b_marketplace.shop.layout.header.account-item.after') !!}
@endif
