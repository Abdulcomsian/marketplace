@extends('admin::layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.suppliers.category.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('b2b_marketplace.admin.supplier.category.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ route('admin.dashboard.index') }}';"></i>

                        {{ __('b2b_marketplace::app.admin.suppliers.category.add-title') }}

                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('b2b_marketplace::app.admin.suppliers.category.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                            <div class="control-group" :class="[errors.has('supplier_id') ? 'has-error' : '']">
                                <label for="supplier_id" class="required">{{ __('b2b_marketplace::app.admin.suppliers.category.supplier') }}</label>
                                <select name="supplier_id" id="supplier_id" class="control" v-validate="'required'"
                                data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.suppliers.category.supplier') }}&quot;"
                                >
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}"> {{$supplier->name}}</option>
                                    @endforeach

                                </select>

                                <span class="control-error" v-if="errors.has('supplier_id')">@{{ errors.first('supplier_id') }}</span>
                            </div>

                            @if ($categories->count())
                                <tree-view behavior="normal" value-field="id" name-field="categories" input-type="checkbox" items='@json($categories)' value='' fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>
                            @endif
                </div>
            </div>
        </form>
    </div>
@stop