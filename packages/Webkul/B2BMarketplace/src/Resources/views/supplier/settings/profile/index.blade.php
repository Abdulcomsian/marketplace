@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.admin.supplier.update-supplier-profile') }}
@stop

@section('content')

    <div class="content">

            <form method="post" action="{{ route('b2b_marketplace.supplier.profile.update') }}" enctype="multipart/form-data" @submit.prevent="onSubmit">
                @csrf
                <div class="page-header">
                    <div class="page-title">
                        <h1>{{ __('b2b_marketplace::app.admin.supplier.update-supplier-profile', ['supplier_name' => $supplier->first_name]) }}</h1>
                    </div>

                    <div class="page-action">
                        <button type="submit" class="btn btn-primary btn-lg">
                            {{ __('b2b_marketplace::app.admin.supplier.save-btn-title') }}
                        </button>
                    </div>
                </div>

                <div class="page-content">

                    {!! view_render_event('admin.b2b_marketplace.supplier.edit.general.before', ['supplier' => $supplier]) !!}
                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label>{{ __('b2b_marketplace::app.shop.supplier.account.profile.profile') }}
                                <image-wrapper  
                                    :button-label="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.add-image-btn-title') }}'" 
                                    input-name="profile" 
                                    :multiple="false" 
                                    :images='"{{ $supplier->addresses->profile_url() }}"'
                                    >
                                </image-wrapper>
                            </div>

                            <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                                <label for="company_name" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.company_name') }}</label>
                                <input type="text" class="control" name="company_name" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.company_name') }}&quot;" value="{{ old('company_name') ?: $supplier->company_name }}">
                                <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                            </div>

                            @if($supplier->url != null
                                && core()->getConfigData('b2b_marketplace.settings.supplier_profile_page.rewrite_shop_url') == 0)

                                <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']">
                                    <label for="url" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}</label>
                                    <input type="text" class="control" disabled name="url" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}&quot;" value="{{ old('url') ?: $supplier->url }}" {{ 'v-slugify'}}>
                                    <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                                </div>

                            @else
                                <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']">
                                    <label for="url" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}</label>
                                    <input type="text" class="control"  name="url" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.url') }}&quot;" value="{{ old('url') ?: $supplier->url }}" {{ 'v-slugify'}}>
                                    <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                                </div>

                                <input type="hidden" value="1" name="is_newurl">
                            @endif

                            {{--  --}}
                            <div class="control-group" :class="[errors.has('company_tag_line') ? 'has-error' : '']">
                                <label for="company-tag-line" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.company-tag-line') }}</label>
                                <input type="text" class="control" name="company_tag_line" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.company-tag-line') }}&quot;" value="{{ old('company_tag_line') ?: $supplier->addresses->company_tag_line }}">
                                <span class="control-error" v-if="errors.has('company_tag_line')">@{{ errors.first('company_tag_line') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('registerd_in') ? 'has-error' : '']">
                                <label for="registerd_in" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.registerd-in') }}</label>
                                <input type="text" class="control" name="registerd_in" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.registerd-in') }}&quot;" value="{{ old('registerd_in') ?: $supplier->addresses->registerd_in }}">
                                <span class="control-error" v-if="errors.has('registerd_in')">@{{ errors.first('registerd_in') }}</span>
                            </div>

                            <div class="control-group"  :class="[errors.has('designation') ? 'has-error' : '']">
                                <label for="designation" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.designation') }}</label>
                                <input type="text" class="control" name="designation" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.designation') }}&quot;" value="{{ old('designation') ?: $supplier->addresses->designation }}">
                                <span class="control-error" v-if="errors.has('designation')">@{{ errors.first('designation') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('team_size') ? 'has-error' : '']">
                                <label for="team_size" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.team-size') }}</label>
                                <input type="text" class="control" name="team_size" v-validate="'required|numeric|min_value:1'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.team-size') }}&quot;" value="{{ old('team_size') ?: $supplier->addresses->team_size }}">
                                <span class="control-error" v-if="errors.has('team_size')">@{{ errors.first('team_size') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('certification') ? 'has-error' : '']">
                                <label for="certification" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.certification') }}</label>
                                <input type="text" class="control" name="certification" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.certification') }}&quot;" value="{{ old('certification') ?: $supplier->addresses->certification }}">
                                <span class="control-error" v-if="errors.has('certification')">@{{ errors.first('certification') }}</span>
                            </div>

                            <div class="control-group"  :class="[errors.has('response_time') ? 'has-error' : '']">
                                <label for="response_time" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.response_time') }}</label>
                                <input type="text" class="control" name="response_time" v-validate="'required|decimal'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.response_time') }}&quot;" value="{{ old('response_time') ?: $supplier->addresses->response_time }}">
                                <span class="control-error" v-if="errors.has('response_time')">@{{ errors.first('response_time') }}</span>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('admin.marketplace.supplier.edit.general.after', ['supplier' => $supplier]) !!}

                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.operational-add') }}'" :active="false">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.phone') }}</label>
                                <input type="text" class="control" name="phone" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.phone') }}&quot;" value="{{ old('phone') ?: $supplier->addresses->phone }}">
                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('address1') ? 'has-error' : '']">
                                <label for="address1" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.address1') }}</label>
                                <input type="text" class="control" name="address1" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.address1') }}&quot;" value="{{ old('address1') ?: $supplier->addresses->address1 }}">
                                <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="address2">{{ __('b2b_marketplace::app.shop.supplier.account.profile.address2') }}</label>
                                <input type="text" class="control" name="address2" value="{{ old('address2') ?: $supplier->addresses->address2 }}">
                            </div>

                            <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                                <label for="city" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.city') }}</label>
                                <input type="text" class="control" name="city" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.city') }}&quot;" value="{{ old('city') ?: $supplier->addresses->city }}">
                                <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                            </div>

                            @include ('b2b_marketplace::supplier.settings.profile.country-state-default', [
                                'countryCode' => old('country') ?? $supplier->addresses->country,
                                'stateCode' => old('state') ?? $supplier->addresses->state,
                                'defaultCountry' => $defaultCountry
                            ])

                            <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                                <label for="postcode" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.postcode') }}</label>
                                <input type="text" class="control" name="postcode" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.postcode') }}&quot;" value="{{ old('postcode') ?: $supplier->addresses->postcode }}">
                                <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.corporate-add') }}'" :active="false">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('address1') ? 'has-error' : '']">
                                <label for="address1" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.address1') }}</label>
                                <input type="text" class="control" name="corporate_address1" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.address1') }}&quot;" value="{{ old('address1') ?: $supplier->addresses->corporate_address1 }}">
                                <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="address2">{{ __('b2b_marketplace::app.shop.supplier.account.profile.address2') }}</label>
                                <input type="text" class="control" name="corporate_address2" value="{{ old('address2') ?: $supplier->addresses->corporate_address2 }}">
                            </div>

                            <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                                <label for="city" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.city') }}</label>
                                <input type="text" class="control" name="corporate_city" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.city') }}&quot;" value="{{ old('city') ?: $supplier->addresses->corporate_city }}">
                                <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                            </div>

                            @include ('b2b_marketplace::supplier.settings.profile.country-state', [
                                'countryCode' => old('corporate_country') ?? $supplier->addresses->corporate_country,
                                'stateCode' => old('corporate_state') ?? $supplier->addresses->corporate_state,
                                'defaultCountry' => $defaultCountry
                            ])

                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.phone') }}</label>
                                <input type="text" class="control" name="corporate_phone" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.phone') }}&quot;" value="{{ old('phone') ?: $supplier->addresses->corporate_phone }}">
                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                                <label for="postcode" class="required">{{ __('b2b_marketplace::app.shop.supplier.account.profile.postcode') }}</label>
                                <input type="text" class="control" name="corporate_postcode" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.profile.postcode') }}&quot;" value="{{ old('postcode') ?: $supplier->addresses->corporate_postcode }}">
                                <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('admin.marketplace.supplier.edit.media.before', ['supplier' => $supplier]) !!}

                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.media') }}'" :active="false">
                        <div slot="body">

                            <div class="control-group">
                                <label>{{ __('b2b_marketplace::app.shop.supplier.account.profile.logo') }}

                                <image-wrapper :button-label="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.add-image-btn-title') }}'" input-name="logo" :multiple="false" :images='"{{ $supplier->addresses->logo_url }}"'></image-wrapper>
                            </div>

                            <div class="control-group">
                                <label>{{ __('b2b_marketplace::app.shop.supplier.account.profile.banner') }}

                                <image-wrapper :button-label="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.add-image-btn-title') }}'" input-name="banner" :multiple="false" :images='"{{ $supplier->addresses->banner_url }}"'></image-wrapper>
                            </div>

                        </div>
                    </accordian>

                    {!! view_render_event('admin.marketplace.supplier.edit.media.after', ['supplier' => $supplier]) !!}

                    {!! view_render_event('admin.marketplace.supplier.edit.about.before', ['supplier' => $supplier]) !!}

                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.about') }}'" :active="false">
                        <div slot="body">

                            <div class="control-group">
                                <label for="company_overview">{{ __('b2b_marketplace::app.shop.supplier.account.profile.company-overview') }}</label>
                                <textarea class="control" id="company_overview" name="company_overview">{{ old('company_overview') ?: $supplier->addresses->company_overview }}</textarea>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('admin.marketplace.supplier.edit.about.after', ['supplier' => $supplier]) !!}

                    {!! view_render_event('admin.marketplace.supplier.edit.social_links.before', ['supplier' => $supplier]) !!}

                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.social_links') }}'" :active="false">
                        <div slot="body">

                            <div class="control-group">
                                <label for="twitter">{{ __('b2b_marketplace::app.shop.supplier.account.profile.twitter') }}</label>
                                <input type="text" class="control" name="twitter" value="{{ old('twitter') ?: $supplier->addresses->twitter }}">
                            </div>

                            <div class="control-group">
                                <label for="facebook">{{ __('b2b_marketplace::app.shop.supplier.account.profile.facebook') }}</label>
                                <input type="text" class="control" name="facebook" value="{{ old('facebook') ?: $supplier->addresses->facebook }}">
                            </div>

                            <div class="control-group">
                                <label for="youtube">{{ __('b2b_marketplace::app.shop.supplier.account.profile.youtube') }}</label>
                                <input type="text" class="control" name="youtube" value="{{ old('youtube') ?: $supplier->addresses->youtube }}">
                            </div>

                            <div class="control-group">
                                <label for="instagram">{{ __('b2b_marketplace::app.shop.supplier.account.profile.instagram') }}</label>
                                <input type="text" class="control" name="instagram" value="{{ old('instagram') ?: $supplier->addresses->instagram }}">
                            </div>

                            <div class="control-group">
                                <label for="skype">{{ __('b2b_marketplace::app.shop.supplier.account.profile.skype') }}</label>
                                <input type="text" class="control" name="skype" value="{{ old('skype') ?: $supplier->addresses->skype }}">
                            </div>

                            <div class="control-group">
                                <label for="linked_in">{{ __('b2b_marketplace::app.shop.supplier.account.profile.linked_in') }}</label>
                                <input type="text" class="control" name="linked_in" value="{{ old('linked_in') ?: $supplier->addresses->linked_in }}">
                            </div>

                            <div class="control-group">
                                <label for="pinterest">{{ __('b2b_marketplace::app.shop.supplier.account.profile.pinterest') }}</label>
                                <input type="text" class="control" name="pinterest" value="{{ old('pinterest') ?: $supplier->addresses->pinterest }}">
                            </div>

                        </div>
                    </accordian>

                    {!! view_render_event('admin.marketplace.supplier.edit.social_links.after', ['supplier' => $supplier]) !!}

                    {!! view_render_event('admin.marketplace.supplier.edit.policies.before', ['supplier' => $supplier]) !!}

                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.policies') }}'" :active="false">
                        <div slot="body">

                            <div class="control-group">
                                <label for="return_policy">{{ __('b2b_marketplace::app.shop.supplier.account.profile.return_policy') }}</label>
                                <textarea class="control" id="return_policy" name="return_policy">{{ old('return_policy') ?: $supplier->addresses->return_policy }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="shipping_policy">{{ __('b2b_marketplace::app.shop.supplier.account.profile.shipping_policy') }}</label>
                                <textarea class="control" id="shipping_policy" name="shipping_policy">{{ old('shipping_policy') ?: $supplier->addresses->shipping_policy }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="privacy_policy">{{ __('b2b_marketplace::app.shop.supplier.account.profile.privacy_policy') }}</label>
                                <textarea class="control" id="privacy_policy" name="privacy_policy">{{ old('privacy_policy') ?: $supplier->addresses->privacy_policy }}</textarea>
                            </div>

                        </div>
                    </accordian>

                    {!! view_render_event('admin.marketplace.supplier.edit.policies.after', ['supplier' => $supplier]) !!}

                    {!! view_render_event('admin.marketplace.supplier.edit.seo.before', ['supplier' => $supplier]) !!}

                    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.profile.seo') }}'" :active="false">
                        <div slot="body">

                            <div class="control-group">
                                <label for="meta_title">{{ __('b2b_marketplace::app.shop.supplier.account.profile.meta_title') }}</label>
                                <textarea class="control" id="meta_title" name="meta_title">{{ old('meta_title') ?: $supplier->addresses->meta_title }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('b2b_marketplace::app.shop.supplier.account.profile.meta_description') }}</label>
                                <textarea class="control" id="meta_description" name="meta_description">{{ old('meta_description') ?: $supplier->addresses->meta_description }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('b2b_marketplace::app.shop.supplier.account.profile.meta_keywords') }}</label>
                                <textarea class="control" id="meta_keywords" name="meta_keywords">{{ old('meta_keywords') ?: $supplier->addresses->meta_keywords }}</textarea>
                            </div>

                        </div>
                    </accordian>

                    {!! view_render_event('admin.marketplace.supplier.edit.seo.after', ['supplier' => $supplier]) !!}

                </div>
            </form>
        {!! view_render_event('admin.marketplace.supplier.edit.after', ['supplier' => $supplier]) !!}
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#company_overview,textarea#return_policy,textarea#shipping_policy,textarea#privacy_policy',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]',
                templates: [
                    { title: 'Test template 1', content: 'Test 1' },
                    { title: 'Test template 2', content: 'Test 2' }
                ],
            });
        });
    </script>
@endpush
