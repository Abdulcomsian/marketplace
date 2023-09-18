@extends('b2b_marketplace::shop.layouts.master')
@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.login-form.page-title') }}
@endsection

@section('content-wrapper')

    <div class="auth-content">
        <div class="sign-up-text">
            {{ __('b2b_marketplace::app.shop.supplier.login-form.no-account') }} - <a href="{{ route('b2b_marketplace.shop.suppliers.signup.index') }}">{{ __('b2b_marketplace::app.shop.supplier.login-form.title-signup') }}</a>
        </div>

        {!! view_render_event('bagisto.shop.supplier.login.before') !!}

        <form method="POST" action="{{ route('b2b_marketplace.shop.supplier.session.create') }}" @submit.prevent="onSubmit">
            {{ csrf_field() }}
            <div class="login-form">
                <div class="login-text">{{ __('b2b_marketplace::app.shop.supplier.login-form.signin-title') }}</div>

                {!! view_render_event('bagisto.shop.supplier.login_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('b2b_marketplace::app.shop.supplier.login-form.email') }}</label>
                    <input type="text" class="control" name="email" v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.login-form.email') }}&quot;">
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="required">{{ __('b2b_marketplace::app.shop.supplier.login-form.password') }}</label>
                    <input type="password" class="control" name="password" v-validate="'required'" value="{{ old('password') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.login-form.password') }}&quot;">
                    <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.suppliers.login_form_controls.after') !!}

                <div class="forgot-password-link">
                    <a href="{{ route('supplier.forgot-password.create') }}">{{ __('b2b_marketplace::app.shop.supplier.login-form.forgot_pass') }}</a>

                    <div class="mt-10">
                        @if (Cookie::has('enable-resend'))
                            @if (Cookie::get('enable-resend') == true)
                                <a href="{{ route('supplier.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('b2b_marketplace::app.shop.supplier.login-form.resend-verifications') }}</a>
                            @endif
                        @endif
                    </div>
                </div>

                <input class="btn btn-primary btn-lg" type="submit" value="{{ __('b2b_marketplace::app.shop.supplier.login-form.button_title') }}">
            </div>
        </form>

        {!! view_render_event('bagisto.shop.suppliers.login.after') !!}
    </div>

@endsection
