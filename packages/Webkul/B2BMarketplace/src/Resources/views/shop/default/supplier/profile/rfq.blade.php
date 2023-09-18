<div class="rfq-main-container">
    <form method="POST" action="{{route('b2b_marketplace.profile.customers.rfq.store')}}" enctype="multipart/form-data" id="supplier-rfq-form" name="supplier-rfq-form" @submit.prevent="onSubmit">
        @csrf()

        <div class="account-head mb-15">
            <span class="account-heading">
                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
            </span>

            <div class="horizontal-rule"></div>
        </div>

        <div class="main-container">
            <div class="main-container-column">
                <div class="supplier-rfq">
                    <div class="supplier-container">
                        <div class="supplier-request-quote">

                                <input type="hidden" name="supplier_id" value={{$supplier->id}}>

                                <div class="supplier-products-row-container" style="background:white !important; padding: 0px !important">

                                    <h3>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-info') }}</h3>

                                    <div class="supplier-header-txt fieldset">
                                        <div class="control-group" :class="[errors.has('quote_title') ? 'has-error' : '']">
                                            <label class="label-text required">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-title') }}
                                            </label>

                                            <input type="text" v-validate="'required'" style="width:100%;" class="control product-search-box" id="quote_title" name="quote_title" value="{{ old('quote-title') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-title') }}&quot;"/>
                                            <span class="control-error" v-if="errors.has('quote_title')">@{{ errors.first('quote_title') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('quote_brief') ? 'has-error' : '']">
                                            <label for="label-text required" class="required">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-description') }}
                                            </label>
                                            <textarea type="text" style="width:100%;" class="control" id="quote_brief" name="quote_brief" v-validate="'required'" value="{{ old('quote_brief') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-description') }}&quot;"></textarea>
                                            <span class="control-error" v-if="errors.has('quote_brief')">@{{ errors.first('quote_brief') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="supplier-products-row-container" style="background:white !important; padding: 0px !important">
                                    <h2>
                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-info') }}
                                    </h2>

                                    <div class="supplier-header-txt fieldset">
                                        <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                            <label class="label-text required">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.name') }}
                                            </label>

                                            <input type="text" v-validate="'required'" style="width:100%;" class="control product-search-box" id="name" name="name" value="{{ old('name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.name') }}&quot;"/>
                                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                                            <label class="label-text required">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.company-name') }}
                                            </label>

                                            <input type="text" v-validate="'required'" style="width:100%;" class="control product-search-box" id="company_name" name="company_name" value="{{ old('company_name') }}" data-vv-as="&quot;Company Name&quot;"/>
                                            <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('address') ? 'has-error' : '']">
                                            <label class="label-text required">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.address') }}
                                            </label>

                                            <input type="text" v-validate="'required'" style="width:100%;" class="control product-search-box" id="address" name="address" value="{{ old('address') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.address') }}&quot;"/>
                                            <span class="control-error" v-if="errors.has('address')">@{{ errors.first('address') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('contact_number') ? 'has-error' : '']">
                                            <label class="label-text required">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-number') }}
                                            </label>

                                            <input type="text" v-validate="'required'" style="width:100%;" class="control product-search-box" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.contact-number') }}&quot;"/>
                                            <span class="control-error" v-if="errors.has('contact_number')">@{{ errors.first('contact_number') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="wk-supplier-btn-wrapper">

                                    <div class="control-group" :class="[errors.has('files') ? 'has-error' : '']">
                                        <label class="label-text">
                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-attachment') }}
                                        </label>

                                        <input type="file" name="files" v-validate class="control file-field" id="rfq-file-field" data-vv-as="File" data-url="" data-vv-rules="" style="width:100%;">

                                        <span class="ddd" style="display: block; color: #ff5656;
                                        margin-top: 5px;" v-if="errors.has('files')">@{{ errors.first('files') }}</span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <accordian  :title="'{{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-quote-product') }}'" :active="true">
                <div slot="header">
                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-quote-product') }}
                    <i class="icon expand-icon right"></i>
                </div>
                <div slot="body">
                    <div class="form-container">
                        <product-search></product-search>
                    </div>
                </div>
            </accordian>

        </div>
    </form>
</div>

@push('scripts')

    <script type="text/x-template" id="product-search-template">
        <section  id="rfq-product-form" name="rfq-product-form" data-vv-scope="product-form">
            <div>
                <div class="product-info" style="margin-bottom: 15px;">
                    <span>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-information') }}</span>
                </div>

                <div class="control-group">
                    <table class="rfq-product-table">
                        <thead>
                            <tr>
                                <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}</th>
                                <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-quantity') }}</th>
                                <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.price') }}</th>
                                <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.samples') }}</th>
                                <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.action') }}</th>
                            </tr>
                        </thead>

                        <tbody class="rfq-product-table-tbody">
                            <tr id="table-row-id" v-if="selectedProduct != ''" v-for = "product in selectedProduct">
                                <td>@{{product.product_name}}</td>
                                <td>@{{product.quantity}}</td>
                                <td>@{{product.priceperqty}}</td>
                                <td v-if='product.is_sample == 1'>Yes</td>
                                <td v-if='product.is_sample == 0'>No</td>
                                <td>
                                    <span title="Remove" class="icon trash-icon" v-on:click= "deleteProduct(product.product_id)"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="supplier-products-row-container" style="padding: 15px;
                    background: rgb(245, 245, 245);
                    border-radius: 3px;">
                        <div class="supplier-header-txt fieldset">
                            <div class="field-required">
                                {{-- <label class="label-text required">Category</label> --}}
                            </div>

                            <div class="control-group" :class="[errors.has('product-form.billing.name') ? 'has-error' : '']">
                                <label class="label-text required">
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}
                                </label>

                                <input type="text" style="width:100%;" class="control dropdown-toggle"
                                placeholder="enter key word" autocomplete="off" v-model.lazy="term" v-debounce="500" id="billing.name" name="billing.name" v-model="product_name" v-validate="'required'" value="{{ old('product_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('product-form.billing.name')">@{{ errors.first('product-form.billing.name') }}</span>

                                <label class="label-text" style="color:#0041ff" v-show="billing.product_id && billing.product_id != null">
                                    Product Price: @{{formatedPrice}}
                                </label>

                                <div class="dropdown-list bottom-left product-search-list">
                                    <div class="dropdown-container">
                                        <ul>
                                            <li v-if="products.length" class="table dropdown-toggle">
                                                <table id="productTable" class="productTable">
                                                    <tbody>
                                                        <tr v-for='(product, index) in products' v-on:click = "getProductId()" :id="product.id" :name="product.name" :price="product.formated_price">
                                                            <td>
                                                                <img v-if="!product.base_image" src="{{ bagisto_asset('themes/b2b/assets/images/Default-Product-Image.png') }}" style="height: 25px; border: 1px solid black;"/>
                                                                <img v-if="product.base_image" :src="product.base_image" style="box-shadow: 4px 3px 4px 1px #c7c7c7;"/>
                                                            </td>
                                                            <td>
                                                                @{{ product.name }}
                                                            </td>
                                                            <td v-if='product.is_config'>
                                                                As Low As @{{ product.formated_price }}
                                                            </td>
                                                            <td v-else='product.is_config'>
                                                                @{{ product.formated_price }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </li>

                                            <li v-if="!products.length && term.length > 2 && !is_searching">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.no-result-found') }}
                                            </li>

                                            <li v-if="term.length < 3 && !is_searching">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.press-any-key') }}
                                            </li>

                                            <li v-if="is_searching">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.searching') }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group" :class="[errors.has('product-form.billing.description') ? 'has-error' : '']">
                                <label for="label-text required" class="required">
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-description') }}
                                </label>
                                <textarea type="text" style="width:100%;" class="control" id="billing.description" name="billing.description" v-model="billing.description" v-validate="'required'" value="{{ old('description') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-description') }}&quot;" data-vv-scope="product-form"></textarea>
                                <span class="control-error" v-if="errors.has('product-form.billing.description')">@{{ errors.first('product-form.billing.description') }}</span>
                            </div>


                            <input type ="hidden" v-for="(product, index) in selectedProduct" name= "products[]" :value="JSON.stringify(selectedProduct[index])"/>


                            <div class="control-group" :class="[errors.has('product-form.billing.quantity') ? 'has-error' : '']">
                                <label class="label-text required">
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}
                                </label>

                                <input type="text" style="width:100%;" class="control product-search-box" id="billing.quantity" name="billing.quantity" v-model="billing.quantity" v-validate="'required|numeric'" value="{{ old('quantity') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}&quot;" data-vv-scope="product-form"/>
                                <span class="control-error" v-if="errors.has('product-form.billing.quantity')">@{{ errors.first('product-form.billing.quantity') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('product-form.billing.priceperqty') ? 'has-error' : '']">
                                <label class="label-text required">
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.price-per-qty') }}
                                </label>

                                <input type="text" style="width:100%;"class="control product-search-box" id="billing.priceperqty" name="billing.priceperqty" v-model="billing.priceperqty" v-validate="'required|decimal'" value="{{ old('priceperqty') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.price-per-qty') }}&quot;" data-vv-scope="product-form"/>

                                <span class="control-error" v-if="errors.has('product-form.billing.priceperqty')">@{{ errors.first('product-form.billing.priceperqty') }}</span>
                            </div>

                        </div>

                        <div class="supplier-header-txt fieldset">
                            <div class="control-group" :class="[errors.has('product-form.billing.is_sample') ? 'has-error' : '']">
                                <label for="is_sample" class="required">
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.is-samples') }}
                                </label>
                                <select style="width:100%;" class="control" v-validate="'required'" id="is_sample" name="billing.is_sample" v-model="billing.is_sample" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.is-samples') }}&quot;" data-vv-scope="product-form">
                                    <option value="1">{{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-require-yes') }}</option>
                                    <option value="0">{{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-require-no') }}</option>
                                </select>


                                <span class="control-error" v-if="errors.has('product-form.billing.is_sample')">@{{ errors.first('product-form.billing.is_sample') }}</span>
                            </div>
                        </div>



                        <div class="supplier-header-txt fieldset">
                            <div class="add-btn">
                                <button v-if="billing.product_id && billing.product_id != null" type="button" id="rfq-add-product" class="btn btn-lg btn-primary" @click= "validateForm('product-form')">
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-product') }}
                                </button>

                                <button v-else="billing.product_id && billing.product_id != null" disabled type="button" id="rfq-add-product" class="btn btn-lg btn-primary" @click= "validateForm('product-form')">
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-product') }}
                                </button>
                            </div>
                        </div>

                        <div class="hr-line" style="border-bottom: 1px solid #aea1a1;
                        margin-bottom: 15px;"></div>

                        <div class="field">
                            <div class="account-content">
                                <div class="account-layout">
                                    <div class="account-table-content">
                                        <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                            <label>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-image') }}

                                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :multiple="true"></image-wrapper>

                                            <span class="control-error" v-if="{!! $errors->has('images.*') !!}">
                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hr-line" style="border-bottom: 1px solid #aea1a1;
                        margin-bottom: 15px;"></div>

                    <div class="supplier-header-txt fieldset">
                        <div class="add-btn">
                            <button type="submit" class="btn btn-lg btn-primary" name="button1" id="btn" :disabled="disable_button">
                                <span>
                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </script>

    <script>

        Vue.component('product-search', {

            template: '#product-search-template',

            inject: ['$validator'],

            data: () => ({
                products: [],

                term: "",

                billing:{
                    'product_id':'',
                    'category_id':[],
                    'selected':'',
                },

                selectedProduct: [],
                checkedCategoryId:[],
                searchQuery: "",

                is_searching: false,
                disable_button: true,
                product_name: "",
                supplierId: '{{$supplier->id}}',
                formatedPrice:'',
            }),

            watch: {
                'term': function(newVal, oldVal) {
                    this.search()
                }
            },

            methods: {
                initialState() {
                    this_this.billing = {

                        'category_id':[],
                        'selected': '',
                        'product_id': '',

                    };

                    this_this.products = [];
                    this_this.product_name = "";
                    this_this.is_searching = false;
                    this_this.formatedPrice='';
                },

                search () {
                    if (this.term.length  >2) {
                        this_this = this;

                        this.is_searching = true;

                        this.$http.post("{{ route('b2b_marketplace.shop.profile.rfq.search') }}", {params: {query: this.term, supplierId: this.supplierId}})
                        .then (function(response) {
                            this_this.products = response.data;

                            this_this.is_searching = false;
                        })

                        .catch (function (error) {
                            this_this.is_searching = false;
                        })
                    }
                },

                validateForm(scope) {

                    var this_this = this;

                    this.$validator.validateAll(scope).then(function (result) {

                        if (result) {
                            if (scope == 'product-form') {

                                this_this.saveProduct();
                            }
                        }
                    });
                },

                getProductId: function () {
                  this_this = this;
                   $(document).on("click",".productTable tr",function() {
                        this_this.billing['product_id'] = $(this).attr('id');
                        this_this.billing['product_name'] = $(this).attr('name');
                        this_this.product_name = $(this).attr('name');
                        this_this.formatedPrice = $(this).attr('price');
                    });

                },

                saveProduct: function() {
                    var this_this = this;
                    this_this.billing['category_id'] = this.checkedCategoryId;

                    this.$http.post ("{{ route('b2b_marketplace.shop.customers.rfq.addproduct') }}",this.billing)
                        .then (function(response) {

                        this_this.selectedProduct.push(response.data.selectedProduct);

                        this_this.initialState();

                        this_this.disable_button= false;

                        window.flashMessages = [{
                            'type': 'alert-success',
                            'message': 'Product Added Successfully.'
                        }];

                        this_this.$root.addFlashMessages();
                    })

                    .catch (function (error) {
                        this_this.is_searching = false;
                    })
                },

                deleteProduct: function(productId) {
                    this_this = this;

                    var product = this_this.selectedProduct;

                    this_this.selectedProduct = this_this.selectedProduct.filter((e)=>e.product_id !== productId);

                    if(this_this.selectedProduct.length < 1) {
                        this_this.disable_button = true;
                    }

                    window.flashMessages = [{
                        'type': 'alert-success',
                        'message': 'Product Removed Successfully.'
                    }];

                    this_this.$root.addFlashMessages();
                }
            }
        });
    </script>
@endpush