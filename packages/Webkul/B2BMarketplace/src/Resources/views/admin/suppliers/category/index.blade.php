@extends('b2b_marketplace::admin.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.suppliers.category.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.admin.suppliers.category.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{route('b2b_marketplace.admin.supplier.category.create')}}" class="btn btn-lg btn-primary">{{ __('b2b_marketplace::app.admin.suppliers.category.create') }}</a>
            </div>
        </div>

        <div class="page-content">

            @inject('supplierCategory', 'Webkul\B2BMarketplace\DataGrids\Admin\AllowedSupplierCategoryDataGrid')
            {!! $supplierCategory->render() !!}

        </div>
    </div>

@stop
