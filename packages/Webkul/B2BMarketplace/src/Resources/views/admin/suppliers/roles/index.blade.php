@extends('admin::layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.suppliers.supplier-roles.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.admin.suppliers.supplier-roles.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('b2b_marketplace.admin.supplier.roles.create') }}" class="btn btn-lg btn-primary">
                    {{ __('Add Role') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('roles','Webkul\B2BMarketplace\DataGrids\Admin\RolesDataGrid')
            {!! $roles->render() !!}
        </div>
    </div>
@stop
