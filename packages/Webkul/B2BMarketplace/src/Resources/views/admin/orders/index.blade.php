@extends('b2b_marketplace::admin.layouts.content')

@section('page_title')
    {{ request()->id ? __('b2b_marketplace::app.admin.orders.manage-title') : __('b2b_marketplace::app.admin.orders.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {{ request()->id ? __('b2b_marketplace::app.admin.orders.manage-title') : __('b2b_marketplace::app.admin.orders.title') }}
                </h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">

            <order-grid></order-grid>

        </div>
    </div>



@stop

@push('scripts')

    <script type="text/x-template" id="order-grid-template">

        <div>
            {!! app('Webkul\B2BMarketplace\DataGrids\Admin\OrderDataGrid')->render() !!}

            <modal id="payForm" :is-open="$root.$root.modalIds.payForm">
                <h3 slot="header">{{ __('b2b_marketplace::app.admin.orders.pay-supplier') }}</h3>

                <div slot="body">
                    <form action="{{ route('admin.b2b_marketplace.orders.pay') }}" method="POST" data-vv-scope="pay-form" @submit.prevent="onSubmit($event)">
                        @csrf

                        <div class="form-container">

                            <input type="hidden" name="order_id" :value="order_id"/>
                            <input type="hidden" name="supplier_id" :value="supplier_id"/>

                            <div class="control-group" :class="[errors.has('pay-form.comment') ? 'has-error' : '']">
                                <label for="comment" class="required">{{ __('b2b_marketplace::app.admin.orders.comment') }}</label>
                                <textarea class="control" name="comment" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.admin.orders.comment') }}&quot;">
                                </textarea>
                                <span class="control-error" v-if="errors.has('pay-form.comment')">@{{ errors.first('pay-form.comment') }}</span>
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary">
                                {{ __('b2b_marketplace::app.admin.orders.pay') }}
                            </button>

                        </div>

                    </form>

                </div>
            </modal>
        </div>

    </script>

    <script>

        Vue.component('order-grid', {
            template: "#order-grid-template",

            data: () => ({
                order_id: null,
                supplier_id: null,
            }),

            created() {
                var this_this = this;

                $(document).ready(function() {
                    $('.pay-btn').on('click', function(e) {
                        this_this.order_id = $(e.target).attr('data-id');

                        this_this.supplier_id = $(e.target).attr('supplier-id');

                        this_this.$root.$root.showModal('payForm')
                    });
                });
            },

            methods: {
                onSubmit (e) {
                    this.$validator.validateAll('pay-form').then((result) => {
                        if (result) {
                            e.target.submit();
                        }
                    });
                }
            }
        });

    </script>

@endpush