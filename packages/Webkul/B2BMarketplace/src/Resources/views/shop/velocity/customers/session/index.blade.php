@extends('shop::layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.login-form.signin-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">

        {!! view_render_event('bagisto.shop.supplier.login.before') !!}

            <div class="container">
                <div class="col-lg-10 col-md-12 offset-lg-1">
                    <div class="heading">
                        <h2 class="fs24 fw6">
                            {{ __('b2b_marketplace::app.shop.supplier.login-form.signin-title')}}
                        </h2>

                        <a href="{{ route('b2b_marketplace.shop.suppliers.signup.index') }}" class="btn-new-customer">
                            <button type="button" class="theme-btn light">
                                {{ __('b2b_marketplace::app.shop.supplier.login-form.title-signup')}}
                            </button>
                        </a>
                    </div>

                    <div class="body col-12">
                        <div class="form-header">
                            <h3 class="fw6">
                                {{ __('velocity::app.customer.login-form.registered-user')}}
                            </h3>

                            <p class="fs16">
                                {{ __('velocity::app.customer.login-form.form-login-text')}}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('b2b_marketplace.shop.supplier.session.create') }}" @submit.prevent="onSubmit">

                            {{ csrf_field() }}

                            {!! view_render_event('bagisto.shop.supplier.login_form_controls.before') !!}

                            <div class="form-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="mandatory label-style">
                                    {{ __('shop::app.customer.login-form.email') }}
                                </label>

                                <input
                                    type="text"
                                    class="form-style"
                                    name="email"
                                    v-validate="'required|email'"
                                    value="{{ old('email') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;" />

                                <span class="control-error" v-if="errors.has('email')" v-text="errors.first('email')"></span>
                            </div>


                            <div class="form-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password" class="mandatory label-style">
                                    {{ __('shop::app.customer.login-form.password') }}
                                </label>

                                <input
                                    type="password"
                                    class="form-style"
                                    name="password"
                                    id="password"
                                    v-validate="'required'"
                                    value="{{ old('password') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.password') }}&quot;" />
                                <input type="checkbox" onclick="myFunction()" id="shoPassword" class="show-password"> {{ __('shop::app.customer.login-form.show-password') }}  
                                <span class="control-error" v-if="errors.has('password')" v-text="errors.first('password')"></span>

                                <a href="{{ route('supplier.forgot-password.create') }}" class="pull-right" 
                                style="float: right!important; margin-top: 10px!important;">
                                    {{ __('b2b_marketplace::app.shop.supplier.login-form.forgot_pass') }}
                                </a>

                                <div class="mt10">
                                    @if (Cookie::has('enable-resend'))
                                        @if (Cookie::get('enable-resend') == true)
                                            <a href="{{ route('supplier.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('b2b_marketplace::app.shop.supplier.login-form.resend-verifications') }}</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                            {!! view_render_event('bagisto.shop.suppliers.login_form_controls.after') !!}

                            <input class="theme-btn" type="submit" value="{{ __('shop::app.customer.login-form.button_title') }}">

                        </form>
                    </div>
                </div>
            </div>

        {!! view_render_event('bagisto.shop.customers.login.after') !!}
    </div>
@endsection

@push('scripts')
    <script>
        $(function(){       
            $(":input[name=email]").focus();
        });

            function myFunction() {
                var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        
    </script>
@endpush
