@extends('shop::customers.account.index')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-layout">
        <div class="account-head mb-10">
            <span class="account-heading">
                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.requested-quote') }}
            </span>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('b2b_marketplace.customer.account.request-quote.before') !!}

        <div class="account-items-list">
            <div class="account-table-content">
                {!! app('Webkul\B2BMarketplace\DataGrids\Shop\RequestForQuoteDataGrid')->render() !!}
            </div>
        </div>

        {!! view_render_event('b2b_marketplace.customer.account.request-quote.after') !!}
    </div>
@endsection