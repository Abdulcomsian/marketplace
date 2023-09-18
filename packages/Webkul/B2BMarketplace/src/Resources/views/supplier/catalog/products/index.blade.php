@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.products.title') }}
@stop

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>{{ __('b2b_marketplace::app.shop.supplier.account.products.title') }}</h1>
        </div>

        <div class="page-action">
            <div class="export-import" @click="showModal('downloadDataGrid')">
                <i class="export-icon"></i>
                <span >
                    {{ __('admin::app.export.export') }}
                </span>
            </div>

            {{-- <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-lg btn-primary">
                {{ __('admin::app.catalog.products.add-product-btn-title') }}
            </a> --}}
        </div>
    </div>

    {!! view_render_event('b2b_marketplace.supplier.catalog.products.list.before') !!}

    <div class="page-content">
        <datagrid-plus src="{{ route('b2b_marketplace.supplier.catalog.products.index') }}"></datagrid-plus>
        @inject('products', 'Webkul\B2BMarketplace\DataGrids\Supplier\ProductDataGrid')
    </div>

    {!! view_render_event('b2b_marketplace.supplier.catalog.products.list.after') !!}
</div>

<modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
    <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
    <div slot="body">
        <export-form></export-form>
    </div>
</modal>
@endsection

@push('scripts')
    @include('admin::export.export', ['gridName' => $products])
@endpush