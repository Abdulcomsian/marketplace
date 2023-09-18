@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.supplier.settings.password-title') }}
@stop

@section('content')
<div class="content">
    <form method="POST" action="{{ route('b2b_marketplace.supplier.profile.password.store', $supplier->id) }}" @submit.prevent="onSubmit">

        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.supplier.settings.password-title') }}</h1>
            </div>

            <div class="page-action">
                <button type="submit" class="btn btn-primary btn-lg">
                    {{ __('b2b_marketplace::app.supplier.settings.save-pswd') }}
                </button>
            </div>
        </div>

        <div class="page-content">
            @csrf()
            <input name="_method" type="hidden" value="PUT">
            <input type="hidden" name="email" value="{{ $supplier->email }}" />

            <accordian :title="'{{ __('admin::app.account.current-password') }}'" :active="true">
                <div slot="body">
                <div class="control-group" :class="[errors.has('current_password') ? 'has-error' : '']">
                    <label for="current_password" class="required">{{ __('admin::app.account.current-password') }}</label>
                    <input type="password" v-validate="'required|min:6'" class="control" id="current_password" name="current_password" data-vv-as="&quot;{{ __('admin::app.account.current-password') }}&quot;"/>
                    <span class="control-error" v-if="errors.has('current_password')">@{{ errors.first('current_password') }}</span>
                </div>
                </div>
            </accordian>

            <accordian :title="'{{ __('b2b_marketplace::app.supplier.settings.account-password') }}'" :active="true">
              <div slot="body">
                  <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                      <label for="password" class="required">{{ __('admin::app.account.password') }}</label>
                      <input type="password" v-validate="'min:6'" class="control" id="password" name="password" ref="password" data-vv-as="&quot;{{ __('admin::app.account.password') }}&quot;"/>
                      <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                  </div>

                  <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                      <label for="password_confirmation" class="required">{{ __('admin::app.account.confirm-password') }}</label>
                      <input type="password" v-validate="'min:6|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation" data-vv-as="&quot;{{ __('admin::app.account.confirm-password') }}&quot;"/>
                      <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                  </div>
              </div>
            </accordian>
        </div>
    </form>
</div>
@endsection
