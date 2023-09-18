{{-- @extends('b2b_marketplace::shop.layouts.account')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.profile.create-title') }}
@endsection

@section('content')

    <div class="account-layout">
        <form method="post" action="" @submit.prevent="onSubmit">
            <div class="account-head mb-10">

                <span class="account-heading">{{ __('b2b_marketplace::app.shop.supplier.account.profile.create-title') }}</span>

                <div class="account-action">
                    @if (! $supplier)
                        <button type="submit" class="btn btn-primary btn-lg">
                            {{ __('b2b_marketplace::app.shop.supplier.account.profile.save-btn-title') }}
                        </button>
                    @endif
                </div>

                <div class="horizontal-rule"></div>

            </div>

            {!! view_render_event('b2b_marketplace.supplier.account.profile.create.before') !!}

            <div class="account-table-content">

                @if (! $supplier)

                    @csrf()

                    <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']">
                        <label for="url" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}</label>
                        <input type="text" class="control" name="url" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}&quot;">
                        <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                    </div>
                @else

                    {{ __('b2b_marketplace::app.shop.supplier.account.profile.waiting-for-approval') }}

                @endif

            </div>


            {!! view_render_event('b2b_marketplace.supplier.account.profile.create.after') !!}

        </form>

    </div>

@endsection --}}