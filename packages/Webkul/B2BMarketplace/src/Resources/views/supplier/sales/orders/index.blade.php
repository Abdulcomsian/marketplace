@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.title') }}
@endsection

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        {!! view_render_event('b2b_marketplace.sellers.account.sales.orders.list.before') !!}

        <div class="page-content">

            {!! app('Webkul\B2BMarketplace\DataGrids\Supplier\OrderDataGrid')->render() !!}

        </div>

        {!! view_render_event('marketplace.sellers.account.sales.orders.list.after') !!}
    </div>

@endsection