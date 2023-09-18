@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.customers.title') }}
@endsection

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.shop.supplier.account.customers.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            {!! app('Webkul\B2BMarketplace\DataGrids\Supplier\CustomerDataGrid')->render() !!}
        </div>
    </div>
@endsection