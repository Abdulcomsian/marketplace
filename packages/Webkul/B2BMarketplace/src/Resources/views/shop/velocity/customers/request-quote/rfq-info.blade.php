<form enctype="multipart/form-data" data-vv-scope="rfq-form">

    {!! view_render_event('b2bmarketplace.shop.customers.rfq.create_form_controls.before') !!}

    <div class="rfq-main-container">

        <div class="main-container">
            <div class="main-container-column">
                <div class="supplier-rfq">
                    <div class="supplier-container">
                        <div class="supplier-request-quote">

                            <div class="supplier-products-row-container">

                                <div class="supplier-header-txt fieldset">

                                    <div class="form-group" :class="[errors.has('rfq-form.quote_title') ? 'has-error' : '']">
                                        <label class="label-text mandatory">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-title') }}
                                        </label>

                                        <input type="text" v-validate="'required'"  class="form-style" id="quote-title" name="quote_title" v-model="rfq_info.quote_title" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-title') }}&quot;"/>
                                        <span class="control-error" v-if="errors.has('rfq-form.quote_title')">@{{ errors.first('rfq-form.quote_title') }}</span>
                                    </div>

                                    <div class="form-group" :class="[errors.has('rfq-form.quote_brief') ? 'has-error' : '']">
                                        <label for="label-text mandatory" class="required">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-description') }}
                                        </label>

                                        <textarea type="text" class="control" id="quote-brief" name="quote_brief" v-validate="'required'" v-model="rfq_info.quote_brief" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-description') }}&quot;"></textarea>
                                        <span class="control-error" v-if="errors.has('rfq-form.quote_brief')">@{{ errors.first('rfq-form.quote_brief') }}</span>
                                    </div>

                                </div>
                            </div>

                            <div class="supplier-products-row-container">
                                <h2>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-info') }}</h2>

                                <div class="supplier-header-txt fieldset">
                                    <div class="control-group" :class="[errors.has('rfq-form.name') ? 'has-error' : '']">
                                        <label class="label-text mandatory">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.name') }}
                                        </label>

                                        <input type="text" v-validate="'required'"  class="control product-search-box" id="name" name="name" v-model="rfq_info.name" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.name') }}&quot;"/>
                                        <span class="control-error" v-if="errors.has('rfq-form.name')">@{{ errors.first('rfq-form.name') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('rfq-form.company_name') ? 'has-error' : '']">
                                        <label class="label-text mandatory">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.company-name') }}
                                        </label>

                                        <input type="text" v-validate="'required'"  class="control product-search-box" id="company_name" name="company_name" v-model="rfq_info.company_name" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.company-name') }}&quot;"/>
                                        <span class="control-error" v-if="errors.has('rfq-form.company_name')">@{{ errors.first('rfq-form.company_name') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('rfq-form.address') ? 'has-error' : '']">
                                        <label class="label-text mandatory">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.address') }}
                                        </label>

                                        <input type="text" v-validate="'required'" class="control product-search-box" id="address" name="address" v-model="rfq_info.address" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.address') }}&quot;"/>
                                        <span class="control-error" v-if="errors.has('rfq-form.address')">@{{ errors.first('rfq-form.address') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('rfq-form.contact_number') ? 'has-error' : '']">
                                        <label class="label-text mandatory">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-number') }}
                                        </label>

                                        <input type="text" v-validate="'required|numeric'" class="control product-search-box" id="contact_number" name="contact_number" v-model="rfq_info.contact_number" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-number') }}&quot;"/>
                                        <span class="control-error" v-if="errors.has('rfq-form.contact_number')">@{{ errors.first('rfq-form.contact_number') }}</span>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</form>