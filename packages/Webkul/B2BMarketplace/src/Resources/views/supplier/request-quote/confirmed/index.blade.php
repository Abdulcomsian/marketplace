@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.supplier.account.rfq.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {{ __('b2b_marketplace::app.supplier.account.rfq.title') }}
                </h1>
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus\ConfirmedDataGrid')->render() !!}
        </div>
    </div>
@endsection