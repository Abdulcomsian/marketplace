@extends('b2b_marketplace::admin.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.products.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.admin.products.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">

            <datagrid-plus src="{{ route('b2b_marketplace.admin.products.index') }}"></datagrid-plus>

        </div>
    </div>

@stop