@extends('b2b_marketplace::admin.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.products.flag.flag-title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.admin.products.flag.flag-title') }}</h1>
            </div>

            <div class="page-action">

            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\B2BMarketplace\DataGrids\Admin\ProductFlagDataGrid')->render() !!}

        </div>
    </div>

@stop