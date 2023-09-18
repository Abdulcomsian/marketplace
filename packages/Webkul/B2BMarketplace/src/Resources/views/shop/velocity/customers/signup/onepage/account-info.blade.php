<form method="post" data-vv-scope="address-form">

        {{ csrf_field() }}

            {!! view_render_event('b2b_marketplace.shop.supplier.signup_form_controls.before') !!}

            <div class="form-group" :class="[errors.has('address-form.email') ? 'has-error' : '']">
                <label for="email" class="mandatory label-style">
                    {{ __('b2b_marketplace::app.shop.supplier.signup-form.email') }}
                </label>
                <input type="email" class="form-style" id="email" name="email" v-model="billing.email" v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.email') }}&quot;">
                <span class="control-error" v-if="errors.has('address-form.email')">@{{ errors.first('address-form.email') }}</span>
            </div>


            <div class="form-group" :class="[errors.has('address-form.billing[password]') ? 'has-error' : '']">
                <label for="password" class="mandatory label-style">
                    {{ __('b2b_marketplace::app.shop.supplier.signup-form.password') }}
                </label>
                <input type="password" class="form-style" id="billing[password]"  name="billing[password]" v-model="billing.password" v-validate="'required|min:6'" ref="password" value="{{ old('password') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.password') }}&quot;">
                <span class="control-error" v-if="errors.has('address-form.billing[password]')">@{{ errors.first('address-form.billing[password]') }}</span>
            </div>

            <div class="form-group" :class="[errors.has('address-form.billing[password_confirmation]') ? 'has-error' : '']">
                <label for="password_confirmation" class="mandatory label-style">
                    {{ __('b2b_marketplace::app.shop.supplier.signup-form.confirm_pass') }}
                </label>
                <input type="password" class="form-style" id="billing[password_confirmation]" name="billing[password_confirmation]" v-model="billing.password_confirmation" v-validate="'required|min:6|confirmed:password'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.confirm_pass') }}&quot;">
                <span class="control-error" v-if="errors.has('address-form.billing[password_confirmation]')">@{{ errors.first('address-form.billing[password_confirmation]') }}</span>
            </div>

            {!! view_render_event('b2b_marketplace.shop.supplier.signup_form_controls.after') !!}
    </form>