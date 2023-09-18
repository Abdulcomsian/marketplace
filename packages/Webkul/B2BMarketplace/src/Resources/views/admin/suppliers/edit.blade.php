@extends('b2b_marketplace::admin.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.supplier.edit-title') }}
@stop

@section('content')

    <div class="content">
        {!! view_render_event('b2bmarketplace.admin.supplier.edit.before', ['supplier' => $supplier]) !!}

        <form method="POST" action="{{ route('b2bmarketplace.admin.supplier.update', $supplier->id) }}"
            @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link"
                            onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('b2b_marketplace::app.admin.supplier.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('b2b_marketplace::app.admin.supplier.save-btn') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.account.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                                <label for="first_name" class="required">
                                    {{ __('b2b_marketplace::app.admin.supplier.first_name') }}</label>
                                <input type="text" class="control" name="first_name" v-validate="'required'"
                                    value="{{ $supplier->first_name }}"
                                    data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.first_name') }}&quot;" />
                                <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                                <label for="last_name" class="required">
                                    {{ __('b2b_marketplace::app.admin.supplier.last_name') }}</label>
                                <input type="text" class="control" name="last_name" v-validate="'required'"
                                    value="{{ $supplier->last_name }}"
                                    data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.last_name') }}&quot;">
                                <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="required">
                                    {{ __('b2b_marketplace::app.admin.supplier.email') }}</label>
                                <input type="email" class="control" name="email" v-validate="'required|email'"
                                    value="{{ $supplier->email }}"
                                    data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.email') }}&quot;">
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="gender"
                                    class="required">{{ __('b2b_marketplace::app.admin.supplier.gender') }}</label>
                                <select name="gender" class="control" value="{{ $supplier->gender }}"
                                    v-validate="'required'"
                                    data-vv-as="&quot;{{ __('shop::app.suppliers.suppliers.gender') }}&quot;">
                                    <option value="Male" {{ $supplier->gender == 'Male' ? 'selected' : '' }}>
                                        {{ __('b2b_marketplace::app.admin.supplier.male') }}</option>
                                    <option value="Female" {{ $supplier->gender == 'Female' ? 'selected' : '' }}>
                                        {{ __('b2b_marketplace::app.admin.supplier.female') }}</option>
                                </select>
                                <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="is_approved"
                                    class="required">{{ __('b2b_marketplace::app.admin.supplier.status') }}</label>
                                <select name="is_approved" class="control" value="{{ $supplier->is_approved }}"
                                    v-validate="'required'"
                                    data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.status') }}&quot;">
                                    <option value="1" {{ $supplier->is_approved == '1' ? 'selected' : '' }}>
                                        {{ __('b2b_marketplace::app.admin.supplier.approved') }}</option>
                                    <option value="0" {{ $supplier->is_approved == '0' ? 'selected' : '' }}>
                                        {{ __('b2b_marketplace::app.admin.supplier.un-approved') }}</option>
                                </select>
                                <span class="control-error" v-if="errors.has('is_approved')">@{{ errors.first('is_approved') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                                <label for="dob">{{ __('b2b_marketplace::app.admin.supplier.date_of_birth') }}</label>
                                <input type="date" class="control" name="date_of_birth"
                                    value="{{ $supplier->date_of_birth }}" v-validate=""
                                    data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.date_of_birth') }}&quot;">
                                <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                            </div>
                        </div>
                    </accordian>


                    <accordian :title="'{{ __('b2b_marketplace::app.admin.suppliers.role') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('role_id') ? 'has-error' : '']">
                                <label for="role" class="required">{{ __('admin::app.users.users.role') }}</label>
                                <select v-validate="'required'" class="control" name="role_id"
                                    data-vv-as="&quot;{{ __('admin::app.users.users.role') }}&quot;">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $supplier->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('role_id')">@{{ errors.first('role_id') }}</span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>

        @if (core()->getConfigData('b2b_marketplace.settings.supplier_flag.status') && $supplier)
            <div class="page-content">

                <div class="form-container">
                    <accordian :title="'{{ __('b2b_marketplace::app.admin.products.flag.flag-title') }}'"
                        :active="'true'">

                        <div slot="body">
                            {!! app('Webkul\B2BMarketplace\DataGrids\Admin\SupplierFlagDataGrid')->render() !!}
                        </div>
                    </accordian>
                </div>
            </div>
        @endif

        {!! view_render_event('b2bmarketplace.admin.supplier.edit.after', ['supplier' => $supplier]) !!}
    </div>

@stop
