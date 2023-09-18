@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.supplier.account.reviews.title') }}
@endsection

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.supplier.account.reviews.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        {!! view_render_event('b2b_marketplace.supplier.account.review.list.before') !!}

        <div class="page-content">

            {!! app('Webkul\B2BMarketplace\DataGrids\Supplier\ReviewDataGrid')->render() !!}

        </div>

        {!! view_render_event('b2b_marketplace.supplier.account.review.list.after') !!}

    </div>
@endsection