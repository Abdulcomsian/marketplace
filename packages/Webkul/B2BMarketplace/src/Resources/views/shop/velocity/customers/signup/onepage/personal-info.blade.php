@push('css')
    <style>
        .text-success {
            color: #4CAF50 !important;
        }

        .text-danger {
            color: #FC6868 !important;
        }
    </style>
@endpush

    <form method="post" data-vv-scope="addressinfo-form">

        {{ csrf_field() }}

        <div class="form-group" :class="[errors.has('addressinfo-form.billing[first_name]') ? 'has-error' : '']">
            <label for="first_name" class="mandatory label-style">
                {{ __('b2b_marketplace::app.shop.supplier.signup-form.firstname') }}
            </label>
            <input type="test" class="form-style" id="billing[first_name]" name="billing[first_name]" v-model="billing.first_name" v-validate="'required'" value="{{ old('first_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.firstname') }}&quot;">
            <span class="control-error" v-if="errors.has('addressinfo-form.billing[first_name]')">@{{ errors.first('addressinfo-form.billing[first_name]') }}</span>
        </div>

        <div class="form-group" :class="[errors.has('addressinfo-form.billing[last_name]') ? 'has-error' : '']">
            <label for="last_name" class="mandatory label-style">
                {{ __('b2b_marketplace::app.shop.supplier.signup-form.lastname') }}
            </label>
            <input type="text" class="form-style" id="billing[last_name]" name="billing[last_name]" v-model="billing.last_name" v-validate="'required'" value="{{ old('last_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.lastname') }}&quot;">

            <span class="control-error" v-if="errors.has('addressinfo-form.billing[last_name]')">@{{ errors.first('addressinfo-form.billing[last_name]') }}</span>
        </div>

        <div class="form-group" :class="[errors.has('addressinfo-form.billing[company_name]') ? 'has-error' : '']">
            <label for="last_name" class="mandatory label-style">
                {{ __('b2b_marketplace::app.shop.supplier.signup-form.company-name') }}
            </label>
            <input type="text" class="form-style" id="billing[company_name]" name="billing[company_name]" v-model="billing.company_name" v-validate="'required'" value="{{ old('company_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.company-name') }}&quot;">

            <span class="control-error" v-if="errors.has('addressinfo-form.billing[company_name]')">@{{ errors.first('addressinfo-form.billing[company_name]') }}</span>
        </div>

        <div class="form-group" :class="[errors.has('addressinfo-form.billing[url]') ? 'has-error' : '']">
            <label for="last_name" class="mandatory label-style">
                {{ __('b2b_marketplace::app.shop.supplier.signup-form.company-url') }}
            </label>

            <input type="text" v-validate="'required'" class="form-style" id="billing[url]" name="billing[url]" v-model="billing.url" value="{{ old('url') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.signup-form.company-url') }}&quot;" v-on:keyup="checkShopUrl($event.target.value)" {{ 'v-slugify'}} :disabled="disable_btn" />
            <span>
                {{ __('b2b_marketplace::app.shop.supplier.signup-form.url-note') }}
            </span>

            <div>
                <span class="control-info text-success" v-if="isShopUrlAvailable != null && isShopUrlAvailable">{{ __('b2b_marketplace::app.shop.supplier.signup-form.shop_url_available') }}</span>

                <span class="control-info text-danger" v-if="isShopUrlAvailable != null && !isShopUrlAvailable">{{ __('b2b_marketplace::app.shop.supplier.signup-form.shop_url_not_available') }}</span>

                <span class="control-error" v-if="errors.has('addressinfo-form.billing[url]')">@{{ errors.first('addressinfo-form.billing[url]') }}</span>
            </div>

        </div>

        <div class="button-group">
            <button :disabled="disable_btn" type="button" class="theme-btn" @click="validateForm('addressinfo-form')" id="checkout-place-order-button">
                {{ __('b2b_marketplace::app.shop.supplier.signup-form.register') }}
            </button>
        </div>
    </form