@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.products.search-title') }}
@endsection

@section('content')

    <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('b2b_marketplace::app.shop.supplier.account.products.add-product') }}
                    </h1>
                </div>

                <div class="page-action">
                    <a href="{{ route('b2b_marketplace.supplier.catalog.products.create') }}" class="btn btn-lg btn-primary">
                        {{ __('b2b_marketplace::app.shop.supplier.account.products.create-new') }}
                    </a>
                </div>
            </div>

            {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.search.before') !!}

            <div class="account-table-content">

                <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.products.search-title') }}'" :active="true">
                    <div slot="body">

                        <div class="info">
                            {!! __('b2b_marketplace::app.shop.supplier.account.products.assign-info') !!}
                        </div>

                        <div class="form-container" style="margin-top: 40px">

                            <product-search></product-search>

                        </div>
                    </div>
                </accordian>

            </div>

            {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.search.after') !!}

    </div>

@endsection

@push('scripts')

    <script type="text/x-template" id="product-search-template">

        <div class="control-group">
            <label for="search">{{ __('b2b_marketplace::app.shop.supplier.account.products.search') }}</label>
            <input type="text" class="control dropdown-toggle" name="search" placeholder="{{ __('b2b_marketplace::app.shop.supplier.account.products.search-term') }}" autocomplete="off" v-model.lazy="term" v-debounce="500"/>

            <div class="dropdown-list bottom-left product-search-list" style="top: 68px; width: 70%;">
                <div class="dropdown-container" style="padding:0px !important;">
                    <ul>
                        <li v-if="products.length" class="table">
                            <table>
                                <tbody>
                                    <tr v-for='(product, index) in products'>
                                        <td>
                                            <img v-if="!product.base_image" src="{{ bagisto_asset('themes/b2b/assets/images/Default-Product-Image.png') }}"/>
                                            <img v-if="product.base_image" :src="product.base_image"/>
                                        </td>
                                        <td>
                                            @{{ product.name }}
                                        </td>
                                        <td>
                                            @{{ product.formated_price }}
                                        </td>
                                        <td class="last">
                                            <a :href="['{{ route('b2b_marketplace.account.products.assign') }}/' + product.id ]" class="btn btn-primary btn-sm">
                                                Sell yours
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>

                        <li v-if="!products.length && term.length > 2 && !is_searching">
                            {{ __('b2b_marketplace::app.shop.supplier.account.products.no-result-found') }}
                        </li>

                        <li v-if="term.length < 3 && !is_searching">
                            {{ __('b2b_marketplace::app.shop.supplier.account.products.enter-search-term') }}
                        </li>

                        <li v-if="is_searching">
                            {{ __('b2b_marketplace::app.shop.supplier.account.products.searching') }}
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </script>

    <script>

        Vue.component('product-search', {

            template: '#product-search-template',

            data: () => ({
                products: [],

                term: "",

                is_searching: false
            }),

            watch: {
                'term': function(newVal, oldVal) {
                    this.search()
                }
            },

            methods: {
                search () {
                    if (this.term.length > 2) {
                        this_this = this;

                        this.is_searching = true;

                        this.$http.get ("{{ route('b2b_marketplace.supplier.catalog.products.search') }}", {params: {query: this.term}})
                            .then (function(response) {
                                this_this.products = response.data;

                                this_this.is_searching = false;
                            })

                            .catch (function (error) {
                                this_this.is_searching = false;
                            })
                    } else if(this_this.products) {
                        this_this.products=[];
                    }
                },
            }
        });


    </script>

@endpush