@extends('b2b_marketplace::shop.layouts.account')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
@endsection

@section('content-wrapper')

<div class="account-content">
    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <form method="POST" action="{{route('b2b_marketplace.shop.customers.rfq.store')}}" enctype="multipart/form-data" id="supplier-rfq-form" name="supplier-rfq-form">

            @csrf()

            <div class="account-head mb-10">
                <span class="account-heading">
                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
                </span>

                <div class="horizontal-rule"></div>
            </div>

            <div class="account-item-list">
                <div class="account-table-content">

                    <product-search categories='@json($categories)'></product-search>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')

<script type="text/x-template" id="product-search-template">

    <div v-if="currentStep == 1" class="rfq-info">

        <accordian  :title="'{{ __('b2b_marketplace::app.shop.supplier.account.rfq.info') }}'" :active="true">

            <div slot="header">
                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.info') }}
                <i class="icon expand-icon right"></i>
            </div>
            <div slot="body">

                <div class="form-container">
                    @include('b2b_marketplace::shop.customers.request-quote.rfq-info')

                    <button class="btn btn-lg btn-primary" type="button" @click="validateRFQ('rfq-form')" >
                        {{ __('Continue') }}
                    </button>
                </div>
            </div>
        </accordian>
    </div>

    <div v-else class="product-info">

        <accordian  :title="'{{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-quote-product') }}'" :active="true">
            <div slot="header">
                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-quote-product') }}
                <i class="icon expand-icon right"></i>
            </div>
            <div slot="body">
                <div class="form-container">
                    @include('b2b_marketplace::shop.customers.request-quote.product-info')

                    <div style="margin: 5px 25px;">

                        <button v-if="product_data.product_id && product_data.product_id != null" type="button" id="rfq-add-product" class="btn btn-lg btn-primary file-field" @click= "validateRFQ('add-product')">
                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-product') }}
                        </button>

                        <button v-else="product_data.product_id && product_data.product_id != null" disabled type="button" id="rfq-add-product" class="btn btn-lg btn-primary file-field">
                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-product') }}
                        </button>
                    </div>

                    <div class="hr-line" style="border-bottom: 1px solid #aea1a1;
                    margin-bottom: 15px;"></div>

                    <div class="field">
                        <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                            <label>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-image') }}

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :multiple="true"></image-wrapper>

                            <span class="control-error" v-if="{!! $errors->has('images.*') !!}">
                            </span>
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
        </accordian>
    </div>
</script>

<script>
    Vue.component('product-search', {
        template: '#product-search-template',

        inject: ['$validator'],

        props: {
            categories: {
                type: [Array, String, Object],
                required: false,
                default: (function () {
                    return [];
                })
            },

            url: String
        },

        data: () => ({
            products: '',
            term: "",
            rfq_info: {},
            product_data:{
                'product_id':'',
                'category_id':[],
                'selected':''
            },

            selectedProduct: [],
            checkedCategoryId: [],
            searchQuery: "",

            is_searching: false,
            disable_button: true,
            product_name: "",
            categoryId: "",
            formatedPrice:'',
            currentStep:1,
            image: ''
        }),

        watch: {
            'term': function(newVal, oldVal) {
                this.search()
            }
        },

        computed: {
            Categories: function() {
                return JSON.parse(this.categories)
            }
        },

        methods: {
            nevigate() {
                this_this = this;
                this_this.currentStep = 1;
            },

            initialState() {
                this_this.product_data = {
                    'category_id': [],
                    'selected': '',
                    'product_id': '',
                };

                this_this.products=[];
                this_this.product_name="";
                this_this.is_searching=false;
                this_this.formatedPrice='';
            },

            search () {
                if (this.term.length  >2) {
                    this_this = this;
                    this.is_searching = true;

                    this.$http.post("{{ route('b2b_marketplace.shop.customers.rfq.search') }}", {query: this.term})
                        .then (function(response) {
                            this_this.products = response.data;
                            this_this.is_searching = false;
                        })

                        .catch (function (error) {
                            this_this.is_searching = false;
                        })
                }
            },

            validateRFQ(scope) {
                var this_this = this;
                this.$validator.validateAll(scope).then(function (result) {

                    if (result) {
                        if (scope == 'rfq-form') {

                            this_this.currentStep = 2;
                        } else {
                            this_this.saveProduct();
                        }
                    }
                });
            },

            getProductId: function () {
                this_this = this;
                $(document).on("click",".productTable tr",function() {
                    this_this.product_data['product_id'] = $(this).attr('id');
                    this_this.product_data['product_name'] = $(this).attr('name');
                    this_this.product_name = $(this).attr('name');
                    this_this.formatedPrice = $(this).attr('price');
                });

            },

            saveProduct: function() {
                var this_this = this;

                this.$validator.validateAll().then(result => {
                    if (result) {
                        if (this.product_data.category_id.length == 0) {

                            window.flashMessages = [{
                            'type': 'alert-error',
                            'message': 'Add Atleast One Category!'
                            }];

                            this.$root.addFlashMessages()
                        } else {
                            this_this.selectedProduct.push(this_this.product_data);

                            this_this.initialState();

                            window.flashMessages = [{
                            'type': 'alert-success',
                            'message': 'Product Added Successfully.'
                            }];

                            this.$root.addFlashMessages()

                            $('.checked').removeClass('checkActive');

                            this_this.disable_button= false;
                        }
                    } else {
                        alert('Field should not be empty!');
                    }
                });
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
                'message': 'Product removed Successfully.'
                }];

                this.$root.addFlashMessages()
            },

            checkedBox: function(checkboxId) {
                this.categoryId = checkboxId;

                if (this.product_data.category_id.includes(this.categoryId)) {
                    for(var i = this.product_data.category_id.length - 1; i >= 0; i--) {
                        if(this.product_data.category_id[i] === this.categoryId ) {

                            delete this.product_data.category_id[i];
                            this.product_data.category_id = this.product_data.category_id.filter(function (category) {
                                return category != null;
                            });

                            document.getElementById(this.categoryId).className='';
                        }
                    }
                } else {
                    $('#'+this.categoryId).addClass('checkActive');
                    this.product_data.category_id.push(this.categoryId);
                }
            },

            isDecimal: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode === 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
        }
    });
</script>
@endpush
