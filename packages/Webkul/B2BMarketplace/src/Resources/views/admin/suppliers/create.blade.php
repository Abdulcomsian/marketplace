@extends('b2b_marketplace::admin.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.suppliers.add-title') }}
@stop

@section('content')

    <div class="content">
        {!! view_render_event('b2bmarketplace.admin.supplier.create.before') !!}

        <form method="POST" action="{{ route('b2b_marketplace.admin.supplier.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('b2b_marketplace::app.admin.suppliers.add-title') }}
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

                    {{-- <input name="_method" type="hidden" value="PUT"> --}}

                    <accordian :title="'{{ __('admin::app.account.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                                <label for="first_name" class="required"> {{ __('b2b_marketplace::app.admin.supplier.first_name') }}</label>
                                <input type="text"  class="control" name="first_name" v-validate="'required'" value="{{ old('first_name') }}"
                                data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.first_name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                                <label for="last_name" class="required"> {{ __('b2b_marketplace::app.admin.supplier.last_name') }}</label>
                                <input type="text"  class="control"  name="last_name"   v-validate="'required'" value="{{ old('first_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.last_name') }}&quot;">
                                <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="required"> {{ __('b2b_marketplace::app.admin.supplier.email') }}</label>
                                <input type="email"  class="control"  name="email" v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.supplier.email') }}&quot;">
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                                <label for="company_name" class="required">
                                    {{ __('b2b_marketplace::app.shop.supplier.signup-form.company-name') }}
                                </label>
                                <input type="text" class="control" id="company_name" name="company_name"  v-validate="'required'" value="{{ old('company_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.company-name') }}&quot;">

                                <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']">
                                <label for="url" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}</label>
                                <input type="text" class="control"  name="url" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}&quot;" value="{{old('url')}}" {{ 'v-slugify'}}>
                                <label>
                                    {{ __('b2b_marketplace::app.shop.supplier.signup-form.url-note') }}
                                </label>

                                <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('role_id') ? 'has-error' : '']">
                                <label for="role" class="required">{{ __('admin::app.users.users.role') }}</label>
                                <select v-validate="'required'" class="control" name="role_id" data-vv-as="&quot;{{ __('admin::app.users.users.role') }}&quot;">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('role_id')">@{{ errors.first('role_id') }}</span>
                            </div>
                        </div>
                    </accordian>

                </div>
            </div>
        </form>

        {!! view_render_event('b2bmarketplace.admin.supplier.create.after') !!}
    </div>

@stop