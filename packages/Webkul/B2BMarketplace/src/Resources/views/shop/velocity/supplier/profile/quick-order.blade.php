<div class="rfq-main-container">

    <div class="main-container">
        <div class="main-container-column">
            <div class="supplier-rfq">
                <div class="supplier-container">
                    <div class="supplier-request-quote">
                        <quick-order></quick-order>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

    <script type="text/x-template" id="quick-order-template">

        <form method="GET" action="" enctype="multipart/form-data" id="quick-order-form" name="quick-order-form" @submit.prevent="quickOrder('order-form')" data-vv-scope="order-form">
        @csrf()

            <div class="form-container">
                <div>
                    <h1>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quick-orders') }}</h1>

                    <h2>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.cart-items') }}</h2>
                    <div class="quick-group" style="width: 70%; padding: 5px;">
                        <table class="rfq-product-table">
                            <thead>
                                <tr>
                                    <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}</th>
                                    <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.cart-quantity') }}</th>
                                    <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.subtotal') }}</th>
                                    <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.action') }}</th>
                                </tr>
                            </thead>

                            @if (count($cartItems) > 0)
                                @foreach ($cartItems as $cartItem)
                                    <tbody class="rfq-product-table-tbody">
                                        <tr id="table-row-id">
                                        <td>{{$cartItem->name}}</td>
                                        <td>{{$cartItem->quantity}}</td>
                                        <td>{{$cartItem->total}}</td>
                                        <td>
                                        <a href="{{route('shop.checkout.cart.remove', $cartItem->id)}}">
                                            <span>
                                            <i class="icon remove-icon"></i>
                                            </span>
                                        </a>
                                        </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            @endif
                        </table>

                        <div class="supplier-products-row-container">
                            <div class="supplier-header-txt fieldset">

                                <div class="form-group" :class="[errors.has('order-form.billing.name') ? 'has-error' : '']">
                                    <label class="label-text mendatory">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}
                                    </label>

                                    <input type="text" class="form-style dropdown-toggle"
                                    placeholder="enter any keyword" autocomplete="off" v-model.lazy="term" v-debounce="500"id="billing.name" name="billing.name" v-model="product_name" v-validate="'required'" value="{{ old('product_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('order-form.billing.name')">@{{ errors.first('order-form.billing.name') }}</span>

                                    <div class="dropdown-list bottom-left product-search-list" style="display: none; top:auto; bottom:auto;">
                                        <div class="velocity-dropdown-container">
                                            <ul style="list-style-type: none;">
                                                <li v-if="products.length" class="table dropdown-toggle">
                                                    <table id="productTable" class="productTable">
                                                        <tbody>
                                                            <tr v-for='(product, index) in products' v-on:click = "addConfigProduct(product)" :id="product.id" :name="product.name">
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

                                                <li v-if="term.length < 3">
                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.press-any-key') }}
                                                </li>

                                                <li v-if="is_searching">
                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.searching') }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="[errors.has('order-form.billing.quantity') ? 'has-error' : '']">
                                    <label class="label-text mendatory">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}
                                    </label>

                                    <input type="text" class="form-style product-search-box" id="billing.quantity" name="billing.quantity" v-model="billing.quantity" v-validate="'required|numeric'" value="{{ old('quantity') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('order-form.billing.quantity')">@{{ errors.first('order-form.billing.quantity') }}</span>
                                </div>

                                <button v-if="billing.product" type="submit" class="theme-btn" name="button2" id="quick-order-submit-btn1">
                                    <span>
                                        {{ __('b2b_marketplace::app.shop.supplier.profile.add-to-cart') }}
                                    </span>
                                </button>

                                <button v-else="billing.product" disabled type="submit" class="theme-btn" name="button2" id="quick-order-submit-btn1">
                                    <span>
                                        {{ __('b2b_marketplace::app.shop.supplier.profile.add-to-cart') }}
                                    </span>
                                </button>

                                <modal id="configProduct" :is-open="$root.$root.modalIds.configProduct">
                                    <h3 slot='header' style="font-size: 16px;" class="label-text mandatory">
                                        {{ __('b2b_marketplace::app.shop.supplier.profile.required-option') }}
                                    </h3>

                                    <div slot="body">

                                        <div class="attributes top-20">
                                            <div v-for='(attribute, index) in childAttributes' class="attribute form-group" :class="[attribute.swatch_type, errors.has('super_attribute[' + attribute.id + ']') ? 'has-error' : '']">
                                                <label class="label-text mandatory">@{{ attribute.label }}</label>

                                                    <div v-if="! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown'" class="store-product-select-box select-box select-box-2">
                                                    <select
                                                        class="form-style"
                                                        v-validate="'required'"
                                                        :name="['super_attribute[' + attribute.id + ']']"
                                                        :disabled="attribute.disabled"
                                                        @change="configure(attribute, $event.target.value)"
                                                        v-model="formData.super_attribute[attribute.id]"
                                                        :id="['attribute_' + attribute.id]"
                                                        :data-vv-as="'&quot;' + attribute.label + '&quot;'">

                                                        <option v-for='(option, index) in attribute.options' :value="option.id">@{{ option.label }}</option>

                                                    </select>
                                                </div>

                                                <span class="swatch-container" v-else>
                                                    <label class="swatch"
                                                        v-for='(option, index) in attribute.options'
                                                        v-if="option.id"
                                                        :data-id="option.id"
                                                        :for="['attribute_' + attribute.id + '_option_' + option.id]">

                                                        <input type="radio"
                                                            v-validate="'required'"
                                                            :name="['super_attribute[' + attribute.id + ']']"
                                                            :id="['attribute_' + attribute.id + '_option_' + option.id]"
                                                            :value="option.id"
                                                            v-model="formData.super_attribute[attribute.id]"
                                                            :data-vv-as="'&quot;' + attribute.label + '&quot;'"
                                                            @change="configure(attribute, $event.target.value)"/>

                                                        <span v-if="attribute.swatch_type == 'color'" :style="{ background: option.swatch_value }"></span>

                                                        <img alt="option-switch-value" v-if="attribute.swatch_type == 'image'" :src="option.swatch_value" />

                                                        <span v-if="! attribute.swatch_type || attribute.swatch_type == 'text' || attribute.swatch_type == 'dropdown'">
                                                            @{{ option.label }}
                                                        </span>

                                                    </label>

                                                    <span v-if="! attribute.options.length" class="no-options">
                                                        {{ __('shop::app.products.select-above-options') }}
                                                    </span>
                                                </span>

                                                <span class="control-error" v-if="errors.has('super_attribute[' + attribute.id + ']')">
                                                    @{{ errors.first('super_attribute[' + attribute.id + ']') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group" :class="[errors.has('quickOrder.formData.quantity') ? 'has-error' : '']">
                                            <label class="label-text mandatory">
                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}
                                            </label>

                                            <input type="text" class="form-style product-search-box" id="formData.quantity" name="formData.quantity" v-model="formData.quantity" v-validate="'required|numeric'" value="{{ old('quantity') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}&quot;"/>
                                            <span class="control-error" v-if="errors.has('quickOrder.formData.quantity')">@{{ errors.first('quickOrder.formData.quantity') }}</span>
                                        </div>

                                        <div>
                                            <button type="button" class="theme-btn" @click= "getVariantProduct(selectedProductId)">
                                                <span>
                                                    {{ __('b2b_marketplace::app.shop.supplier.profile.add-to-cart') }}
                                                </span>
                                            </button>
                                        </div>
                                    </div>

                                </modal>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="supplier_id" value={{ $supplier->id }}>

            <div class="wk-supplier-btn-wrapper" disabled>
                <a  type="submit" class="theme-btn" style="float:right !important;" name="button1" id="quick-order-submit-btn" @if (count($cartItems) == 0) disabled @else href="{{route('shop.checkout.onepage.index')}}" @endif style="float:right !important; margin-bottom: 5px; background: #00BF44 !important;">
                    <span>
                        {{ __('b2b_marketplace::app.shop.supplier.profile.buynow') }}
                    </span>
                </a>
            </div>

            <span class="supplier-hr-line"></span>
        </form>

    </script>

    <script>

        Vue.component('quick-order', {

            template: '#quick-order-template',

            inject: ['$validator'],

            data: () => ({

                supplier: @json($supplier),

                config:'',

                formData: {
                    super_attribute: {},
                },

                childAttributes: [],

                selectedProductId: '',

                simpleProduct: null,

                galleryImages: [],

                products: [],

                term: "",

                billing:{
                    'product':'',

                },

                attribute:{
                    'gender': null,
                    'attribute_id':'',
                },

                searchQuery: "",

                is_searching: false,

                product_name: "",

                attributeId:[],

                option_id: []

            }),

            watch: {
                'term': function(newVal, oldVal) {

                    this.search()
                }
            },

            methods: {
                quickOrder (formScope) {
                    var this_this = this;

                    this.$validator.validateAll(formScope).then(function (result) {
                    var a = formScope;

                        if (result) {
                            if (formScope == 'order-form') {

                                this_this.saveProduct();
                            }
                        }
                    });

                },

                initialState() {

                    var this_this = this;

                    this_this.billing = {
                        'product': '',
                    };

                    this_this.product_name = "";
                    this_this.is_searching = false;
                },

                search () {

                    if (this.term.length >2) {
                        this_this = this;

                        var supplier_id = this_this.supplier.id;

                        this.is_searching = true;

                        this.$http.post("{{ route('b2b_marketplace.shop.customers.quick-order.search') }}", {params: {query: this.term, supplier: supplier_id}})
                        .then (function(response) {
                            this_this.products = response.data;

                            this_this.is_searching = false;
                        })

                        .catch (function (error) {
                            this_this.is_searching = false;
                        })
                    }
                },

                saveProduct: function() {

                    var this_this = this;

                    this.$http.post ("{{ route('b2b_marketplace.shop.profile.quick-order.store') }}",this_this.billing)
                    .then (function(response) {

                        this_this.initialState();

                        window.flashMessages = [{
                            'type': 'alert-success',
                            'message': 'Item Successfully Added To Cart'
                        }];

                        this_this.$root.addFlashMessages();

                        window.location.reload();
                        $("#quick-order").load(window.location.href);
                    })

                    .catch (function (error) {

                        window.flashMessages = [{
                            'type': 'alert-success',
                            'message': 'Requested quantity not available'
                        }];

                        this_this.$root.addFlashMessages();

                        window.location.reload();
                        $("#quick-order").load(window.location.href);

                        this.is_searching = false;
                    })
                },

                getVariantProduct: function (selectedProductId) {

                    this.$http.post ("{{ route('b2b_marketplace.shop.profile.quick-order.addToCart') }}", this.formData)
                    .then (function(response) {
                        if (response.data == true) {

                            window.flashMessages = [{
                                'type': 'alert-success',
                                'message': 'Item Successfully Added To Cart'
                            }];

                            this_this.$root.addFlashMessages();

                            window.location.reload();
                            $("#quick-order").load(window.location.href);
                        }
                    })

                    .catch (function (error) {

                        window.flashMessages = [{
                            'type': 'alert-success',
                            'message': 'Requested quantity not available'
                        }];

                        this_this.$root.addFlashMessages();
                    })
                },

                prepareData () {
                    this.galleryImages = this.product.images.slice(0)

                    var config = jQuery.extend(true, {}, this.config)

                    var childAttributes = this.childAttributes,
                        attributes = config.attributes.slice(),
                        index = attributes.length,
                        attribute;

                    while (index--) {
                        attribute = attributes[index];

                        attribute.options = [];

                        if (index) {
                            attribute.disabled = true;
                        } else {
                            this.fillSelect(attribute);
                        }

                        attribute = Object.assign(attribute, {
                            childAttributes: childAttributes.slice(),
                            prevAttribute: attributes[index - 1],
                            nextAttribute: attributes[index + 1]
                        });

                        childAttributes.unshift(attribute);
                    }
                },

                configure (attribute, value) {
                    this.simpleProduct = this.getSelectedProductId(attribute, value);

                    if (value) {
                        attribute.selectedIndex = this.getSelectedIndex(attribute, value);

                        if (attribute.nextAttribute) {
                            attribute.nextAttribute.disabled = false;

                            this.fillSelect(attribute.nextAttribute);

                            this.resetChildren(attribute.nextAttribute);
                        } else {
                            this.formData['selected_configurable_option'] = attribute.options[attribute.selectedIndex].allowedProducts[0];
                        }
                    } else {
                        attribute.selectedIndex = 0;

                        this.resetChildren(attribute);

                        this.clearSelect(attribute.nextAttribute)
                    }
                },

                getSelectedIndex (attribute, value) {
                    var selectedIndex = 0;

                    attribute.options.forEach(function(option, index) {
                        if (option.id == value) {
                            selectedIndex = index;
                        }
                    })

                    return selectedIndex;
                },

                getSelectedProductId (attribute, value) {
                    var options = attribute.options,
                        matchedOptions;

                    matchedOptions = options.filter(function (option) {
                        return option.id == value;
                    });

                    if (matchedOptions[0] != undefined && matchedOptions[0].allowedProducts != undefined) {
                        return matchedOptions[0].allowedProducts[0];
                    }

                    return undefined;
                },

                fillSelect (attribute) {
                    var options = this.getAttributeOptions(attribute.id),
                        prevOption,
                        index = 1,
                        allowedProducts,
                        i,
                        j;

                    this.clearSelect(attribute)

                    attribute.options = [{ 'id': '', 'label': this.config.chooseText, 'products': [] }];

                    if (attribute.prevAttribute) {
                        prevOption = attribute.prevAttribute.options[attribute.prevAttribute.selectedIndex];
                    }

                    if (options) {
                        for (i = 0; i < options.length; i++) {
                            allowedProducts = [];

                            if (prevOption) {
                                for (j = 0; j < options[i].products.length; j++) {
                                    if (prevOption.products && prevOption.products.indexOf(options[i].products[j]) > -1) {
                                        allowedProducts.push(options[i].products[j]);
                                    }
                                }
                            } else {
                                allowedProducts = options[i].products.slice(0);
                            }

                            if (allowedProducts.length > 0) {
                                options[i].allowedProducts = allowedProducts;

                                attribute.options[index] = options[i];

                                index++;
                            }
                        }
                    }
                },

                resetChildren (attribute) {
                    if (attribute.childAttributes) {
                        attribute.childAttributes.forEach(function (set) {
                            set.selectedIndex = 0;
                            set.disabled = true;
                        });
                    }
                },

                clearSelect: function (attribute) {
                    if (! attribute)
                        return;

                    if (! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown') {
                        var element = document.getElementById("attribute_" + attribute.id);

                        if (element) {
                            element.selectedIndex = "0";
                        }
                    } else {
                        var elements = document.getElementsByName('super_attribute[' + attribute.id + ']');

                        var this_this = this;

                        elements.forEach(function(element) {
                            element.checked = false;
                        })
                    }
                },

                getAttributeOptions (attributeId) {
                    var this_this = this,
                        options;

                    this.config.attributes.forEach(function(attribute, index) {
                        if (attribute.id == attributeId) {
                            options = attribute.options;
                        }
                    })

                    return options;
                },

                addConfigProduct: function (product) {

                    this_this = this;
                    this_this.childAttributes = [];
                    this_this.product_name = product.name;
                    this_this.billing['product'] = product.id;
                    this_this.formData['product'] = product.id;

                    if (product.is_config) {
                        this_this.$root.$root.showModal('configProduct');

                        this.$http.post ("{{ route('b2b_marketplace.shop.profile.quick-order.config.store') }}", product)
                        .then (function(response) {

                            this_this.config = response.data;
                            var config = response.data;

                            var childAttributes = this_this.childAttributes,
                                attributes = config.attributes.slice(),
                                index = attributes.length,
                                attribute;

                            while (index--) {
                                attribute = attributes[index];

                                if (index) {
                                    attribute.disabled = true;
                                } else {

                                    this_this.fillSelect(attribute);
                                }

                                attribute = Object.assign(attribute, {
                                    childAttributes: childAttributes.slice(),
                                    prevAttribute: attributes[index - 1],
                                    nextAttribute: attributes[index + 1]
                                });

                                childAttributes.unshift(attribute);
                            }
                        })

                        .catch (function (error) {
                            console.log('error');
                        })
                    }
                },
            }
        });
    </script>
@endpush