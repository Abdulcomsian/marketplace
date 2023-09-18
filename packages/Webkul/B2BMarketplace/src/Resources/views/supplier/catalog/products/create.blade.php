@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.products.add-title') }}
@endsection

@section('content')

    <div class="content">

        <form method="POST" action="" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.catalog.products.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.save-btn-title') }}
                    </button>
                </div>
            </div>

            {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.create.before') !!}

            <div class="account-table-content">

                @csrf()

                <?php $familyId = app('request')->input('family') ?>
                <?php $sku = app('request')->input('sku') ?>

                {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.create_form_accordian.general.before') !!}

                <accordian :title="'{{ __('admin::app.catalog.products.general') }}'" :active="true">
                    <div slot="header">
                        {{ __('admin::app.catalog.products.general') }}
                        <i class="icon expand-icon right"></i>
                    </div>
                    <div slot="body">

                        {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.general.controls.before') !!}

                        <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                            <label for="type" class="required">{{ __('admin::app.catalog.products.product-type') }}</label>

                            <select class="control" v-validate="'required'" id="type" name="type" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.product-type') }}&quot;">
                                <option value="simple">{{ __('admin::app.catalog.products.type.simple') }}</option>
                                <option value="configurable" {{ $familyId ? 'selected' : '' }}>{{ __('admin::app.catalog.products.type.configurable') }}</option>
                            </select>

                            @if ($familyId)
                                <input type="hidden" name="type" value="configurable"/>
                            @endif
                            <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                            <label for="attribute_family_id" class="required">{{ __('admin::app.catalog.products.familiy') }}</label>
                            <select class="control" v-validate="'required'" id="attribute_family_id" name="attribute_family_id" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.familiy') }}&quot;">
                                <option value=""></option>
                                @foreach ($families as $family)
                                    <option value="{{ $family->id }}" {{ ($familyId == $family->id || old('attribute_family_id') == $family->id) ? 'selected' : '' }}>{{ $family->name }}</option>
                                    @endforeach
                            </select>

                            @if ($familyId)
                                <input type="hidden" name="attribute_family_id" value="{{ $familyId }}"/>
                            @endif
                            <span class="control-error" v-if="errors.has('attribute_family_id')">@{{ errors.first('attribute_family_id') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('sku') ? 'has-error' : '']">
                            <label for="sku" class="required">{{ __('admin::app.catalog.products.sku') }}</label>
                            <input type="text" v-validate="{ required: true, regex: /^[a-z0-9]+(?:-[a-z0-9]+)*$/ }" class="control" id="sku" name="sku" value="{{ $sku ?: old('sku') }}" data-vv-as="&quot;{{ __('admin::app.catalog.products.sku') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('sku')">@{{ errors.first('sku') }}</span>
                        </div>

                        {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.general.controls.after') !!}

                    </div>
                </accordian>

                {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.create_form_accordian.general.after') !!}


                @if ($familyId)

                    {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.create_form_accordian.configurable_attributes.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.products.configurable-attributes') }}'" :active="true">
                        <div slot="header">
                            {{ __('admin::app.catalog.products.configurable-attributes') }}
                            <i class="icon expand-icon right"></i>
                        </div>
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.configurable_attributes.controls.before') !!}

                            <div class="table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin::app.catalog.products.attribute-header') }}</th>
                                            <th>{{ __('admin::app.catalog.products.attribute-option-header') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($configurableFamily->configurable_attributes as $attribute)
                                            <tr>
                                                <td>
                                                    {{ $attribute->admin_name }}
                                                </td>
                                                <td>
                                                    @foreach ($attribute->options as $option)
                                                        <span class="label">
                                                            <input type="hidden" name="super_attributes[{{$attribute->code}}][]" value="{{ $option->id }}"/>
                                                            {{ $option->admin_name }}

                                                            <i class="icon cross-icon"></i>
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td class="actions">
                                                    <i class="icon trash-icon"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.configurable_attributes.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.create_form_accordian.configurable_attributes.after') !!}

                @endif

            </div>

            {!! view_render_event('b2b_marketplace.supplier.account.catalog.product.create.after') !!}

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.label .cross-icon').on('click', function(e) {
                $(e.target).parent().remove();
            })

            $('.actions .trash-icon').on('click', function(e) {
                $(e.target).parents('tr').remove();
            })
        });
    </script>
@endpush