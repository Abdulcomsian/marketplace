@extends('b2b_marketplace::admin.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.products.flag.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.admin.products.flag.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{route('b2b_marketplace.admin.product-flag.reason.create')}}" class="btn btn-lg btn-primary" > {{ __('b2b_marketplace::app.admin.suppliers.flag.add-btn-title') }}</a>
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\B2BMarketplace\DataGrids\Admin\ProductFlagReasonDataGrid')->render() !!}

        </div>
    </div>

@stop