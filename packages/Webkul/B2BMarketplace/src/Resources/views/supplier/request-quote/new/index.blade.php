@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.supplier.account.rfq.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                {{ __('b2b_marketplace::app.supplier.account.rfq.title') }}
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus\NewDataGrid')->render() !!}
        </div>
    </div>
@endsection