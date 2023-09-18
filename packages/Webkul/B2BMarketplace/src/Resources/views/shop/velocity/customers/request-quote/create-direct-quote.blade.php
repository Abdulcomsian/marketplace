@extends('b2b_marketplace::shop.layouts.account')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-layout right m10">

        <form method="POST" action="{{ route('b2b_marketplace.home.products.rfq.store', $product->product_id) }}"
            enctype="multipart/form-data" id="supplier-rfq-form" @submit.prevent="onSubmit">

            @csrf

            <div class="account-head mb-10" style="display: block;">
                <span class="account-heading">
                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
                </span>

                <div class="account-action">
                    <input class="theme-btn seperate" type="submit"
                        value="{{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}">
                </div>

                <div class="horizontal-rule"></div>
            </div>

            <div class="account-table-content">

                {!! view_render_event('b2bmarketplace.shop.product.rfq.create_form_controls.before') !!}

                <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.rfq.info') }}'"
                    :active="true">
                    <div slot="header">
                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.info') }}
                        <i class="icon expand-icon right"></i>
                    </div>
                    <div slot="body">
                        <div class="rfq-main-container">

                            <?php $productBaseImage = productimage()->getProductBaseImage($product); ?>

                            <div class="product-name-container" style="display: flex;">
                                <div class="name"
                                    style="margin-right: 15px;
                                    margin-top: 20px; color:#f82e56;">
                                    {{ __('Product ') }}
                                </div>

                                <div>
                                    <a href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                                        title="{{ $product->name }}">
                                        <img src="{{ $productBaseImage['medium_image_url'] }}" width="70px"
                                            height="70" />
                                    </a>
                                </div>

                                <div class="price" style="margin-top: 20px; margin-left: 20px;">
                                    {{ __('Price ') }}

                                    @include ('shop::products.price', ['product' => $product])
                                </div>
                            </div>

                            <div class="main-container">
                                <div class="main-container-column">
                                    <div class="supplier-rfq">
                                        <div class="supplier-container">
                                            <div class="supplier-request-quote">
                                                @if ($supplier != null)
                                                    <input type="hidden" name="supplier_id" value={{ $supplier->id }}>
                                                @else
                                                    <input type="hidden" name="supplier_id" value="NULL">
                                                @endif
                                                <div class="supplier-products-row-container">

                                                    <div class="supplier-header-txt fieldset">
                                                        <div class="control-group"
                                                            :class="[errors.has('quote_title') ? 'has-error' : '']">
                                                            <label class="label-text required">
                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-title') }}
                                                            </label>

                                                            <input type="text" v-validate="'required'"
                                                                class="control product-search-box" id="quote_title"
                                                                name="quote_title" value="{{ old('quote_title') }}"
                                                                data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-title') }}&quot;" />
                                                            <span class="control-error"
                                                                v-if="errors.has('quote_title')">@{{ errors.first('quote_title') }}</span>
                                                        </div>

                                                        <div class="control-group"
                                                            :class="[errors.has('quote_brief') ? 'has-error' : '']">
                                                            <label for="label-text required" class="required">
                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-description') }}
                                                            </label>
                                                            <textarea type="text" class="control" id="quote_brief" name="quote_brief" v-validate="'required'"
                                                                value="{{ old('quote_brief') }}"
                                                                data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-description') }}&quot;"></textarea>
                                                            <span class="control-error"
                                                                v-if="errors.has('quote_brief')">@{{ errors.first('quote_brief') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="supplier-products-row-container">
                                                    <h2>
                                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-info') }}
                                                    </h2>

                                                    <div class="supplier-header-txt fieldset">
                                                        <div class="control-group"
                                                            :class="[errors.has('name') ? 'has-error' : '']">
                                                            <label class="label-text required">
                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.name') }}
                                                            </label>

                                                            <input type="text" v-validate="'required'"
                                                                class="control product-search-box" id="name"
                                                                name="name" value="{{ old('name') }}"
                                                                data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.name') }}&quot;" />
                                                            <span class="control-error"
                                                                v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                                                        </div>

                                                        <div class="control-group"
                                                            :class="[errors.has('company_name') ? 'has-error' : '']">
                                                            <label class="label-text required">
                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.company-name') }}
                                                            </label>

                                                            <input type="text" v-validate="'required'"
                                                                class="control product-search-box" id="company_name"
                                                                name="company_name" value="{{ old('company_name') }}"
                                                                data-vv-as="&quot;Company Name&quot;" />
                                                            <span class="control-error"
                                                                v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                                                        </div>

                                                        <div class="control-group"
                                                            :class="[errors.has('address') ? 'has-error' : '']">
                                                            <label class="label-text required">
                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.address') }}
                                                            </label>

                                                            <input type="text" v-validate="'required'"
                                                                class="control product-search-box" id="address"
                                                                name="address" value="{{ old('address') }}"
                                                                data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.address') }}&quot;" />
                                                            <span class="control-error"
                                                                v-if="errors.has('address')">@{{ errors.first('address') }}</span>
                                                        </div>

                                                        <div class="control-group"
                                                            :class="[errors.has('contact_number') ? 'has-error' : '']">
                                                            <label class="label-text required">
                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-number') }}
                                                            </label>

                                                            <input type="text" v-validate="'required'"
                                                                class="control product-search-box" id="contact_number"
                                                                name="contact_number" value="{{ old('contact_number') }}"
                                                                data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-number') }}&quot;" />
                                                            <span class="control-error"
                                                                v-if="errors.has('contact_number')">@{{ errors.first('contact_number') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="wk-supplier-btn-wrapper">

                                                    <div class="control-group"
                                                        :class="[errors.has('files') ? 'has-error' : '']">
                                                        <label class="label-text">
                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-attachment') }}
                                                        </label>

                                                        <input type="file" name="files" v-validate
                                                            class="control file-field" id="rfq-file-field"
                                                            data-vv-as="File" data-url="" data-vv-rules="">

                                                        <span class="ddd"
                                                            style="display: block; color: #ff5656;
                                                            margin-top: 5px;"
                                                            v-if="errors.has('files')">@{{ errors.first('files') }}</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-information') }}'"
                    :active="true">

                    <div slot="header">
                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-information') }}
                        <i class="icon expand-icon right"></i>
                    </div>

                    <div slot="body">

                        <div class="form-container">
                            <div class="rfq-product-section">

                                <div class="supplier-header-txt fieldset">

                                    <input type="hidden" name="products[product_name]" value="{{ $product->name }}">

                                    <input type="hidden" name="products[product_id]"
                                        value="{{ $product->product_id }}">

                                    <input type="hidden" name="products[category_id]" value="{{ $categories }}">

                                    <div class="control-group"
                                        :class="[errors.has('products[description]') ? 'has-error' : '']">
                                        <label class="label-text required">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-description') }}
                                        </label>
                                        <textarea type="text" class="control" id="description" name="products[description]" v-validate="'required'"
                                            value="{{ old('description') }}"
                                            data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-description') }}&quot;"></textarea>
                                        <span class="control-error"
                                            v-if="errors.has('products[description]')">@{{ errors.first('products[description]') }}</span>
                                    </div>

                                    <div class="control-group"
                                        :class="[errors.has('products[quantity]') ? 'has-error' : '']">
                                        <label class="label-text required">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}
                                        </label>

                                        <input type="text" class="control product-search-box" id="quantity"
                                            name="products[quantity]" v-validate="'required|numeric|min_value:1'"
                                            value="{{ old('quantity') }}"
                                            data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}&quot;" />
                                        <span class="control-error"
                                            v-if="errors.has('products[quantity]')">@{{ errors.first('products[quantity]') }}</span>
                                    </div>

                                    <div class="control-group"
                                        :class="[errors.has('products[priceperqty]') ? 'has-error' : '']">
                                        <label class="label-text required">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.price-per-qty') }}
                                        </label>

                                        <input type="text" class="control product-search-box" id="priceperqty"
                                            name="products[priceperqty]" v-validate="'required|numeric'"
                                            value="{{ old('priceperqty') }}"
                                            data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.price-per-qty') }}&quot;">

                                        <span class="control-error"
                                            v-if="errors.has('products[priceperqty]')">@{{ errors.first('products[priceperqty]') }}</span>
                                    </div>

                                </div>

                                <div class="supplier-header-txt fieldset">

                                    <div class="control-group"
                                        :class="[errors.has('products[is_sample]') ? 'has-error' : '']">
                                        <label for="is_sample" class="required">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.is-samples') }}
                                        </label>
                                        <select class="control" v-validate="'required'" id="is_sample"
                                            name="products[is_sample]"
                                            data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.is-samples') }}&quot;">
                                            <option value="1">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-require-yes') }}
                                            </option>
                                            <option value="0">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-require-no') }}
                                            </option>
                                        </select>


                                        <span class="control-error"
                                            v-if="errors.has('products[is_sample]')">@{{ errors.first('products[is_sample]') }}</span>
                                    </div>

                                    <div class="field">
                                        <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                            <label>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-image') }}

                                                <image-wrapper
                                                    :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'"
                                                    input-name="images" :multiple="true"></image-wrapper>

                                                <span class="control-error" v-if="{!! $errors->has('images.*') !!}">
                                                </span>

                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line"
                                    style="border-bottom: 1px solid #aea1a1;
                                    margin-bottom: 15px;">
                                </div>
                            </div>
                        </div>

                    </div>
                </accordian>

                {!! view_render_event('b2bmarketplace.shop.product.rfq.create_form_controls.after') !!}

            </div>
        </form>
    </div>
@endsection
