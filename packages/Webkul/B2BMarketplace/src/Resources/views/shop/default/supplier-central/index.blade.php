@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.b2b-marketplace.title') }}
@stop

@push('css')

    <style>
        .feature-icon {
            height: 124px;
            width: 124px;
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
    </style>
@endpush

@section('content-wrapper')

    <div class="main seller-central-container">

        @if (core()->getConfigData('b2b_marketplace.settings.landing_page.show_banner'))
            <div class="banner-container">

                @if (core()->getConfigData('b2b_marketplace.settings.landing_page.banner'))
                    <img class="banner"
                        src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.banner')) }}" />
                @else
                    <img class="banner" src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/banner-3.png" />
                @endif

                <div class="banner-content">
                    <h1>
                        {{ core()->getConfigData('b2b_marketplace.settings.landing_page.page_title') ?? __('b2b-marketplace::app.shop.b2b-marketplace.title') }}
                    </h1>

                    @if ($bannerContent = core()->getConfigData('b2b_marketplace.settings.landing_page.banner_content'))
                        <p>
                            {!! $bannerContent !!}
                        </p>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.show_open_shop_block'))
                        <a target="new" href="{{ route('b2b_marketplace.shop.suppliers.signup.index') }}"
                            class="btn btn-black btn-lg">
                            {{ core()->getConfigData('b2b_marketplace.settings.landing_page.open_shop_button_label') ?? __('b2b-marketplace::app.shop.b2b-marketplace.open-shop-label') }}
                        </a>
                    @endif
                </div>

            </div>
        @endif

        @if (core()->getConfigData('b2b_marketplace.settings.landing_page.show_features'))
            <div class="feature-container">
                <div class="feature-heading">
                    <h2>{{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_heading') ?? __('b2b-marketplace::app.shop.b2b-marketplace.features') }}
                    </h2>

                    <p>{{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_info') ?? __('b2b-marketplace::app.shop.b2b-marketplace.features-info') }}
                    </p>
                </div>

                <ul class="feature-list">
                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_1') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_1'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_1')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_1') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_2') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_2'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_2')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_2') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_3') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_3'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_3')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_3') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_4') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_4'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_4')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_4') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_5') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_5'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_5')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_5') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_6') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_6'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_6')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_6') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_7') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_7'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_7')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_7') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_8') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_8'))
                        <li>
                            <img class="feature-icon"
                                src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_8')) }}" />

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_8') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_9') &&
                        core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_9'))
                        <li>
                            @if (core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_9') ==
                                'configuration/Icon-RFQ-SELL.svg')
                                <img class="feature-icon"
                                    src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_9')) }}"
                                    style="width:100px;" />
                            @else
                                <img class="feature-icon"
                                    src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_9')) }}" />
                            @endif

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.landing_page.feature_icon_label_9') }}
                            </div>
                        </li>
                    @endif
                </ul>

            </div>
        @endif

        @if (core()->getConfigData('b2b_marketplace.settings.landing_page.show_popular_sellers'))

            @php $popularSuppliers = app('Webkul\B2BMarketplace\Repositories\SupplierRepository')->getPopularSellers(); @endphp

            @if ($popularSuppliers->count())
                <div class="popular-sellers-container">
                    <div class="popular-sellers-heading">
                        {{ __('b2b_marketplace::app.shop.supplier.profile.popular-supplier') }}
                    </div>

                    <div class="popular-sellers-list">

                        @foreach ($popularSuppliers as $supplier)
                            @php
                                if ($supplier->is_approved == 0) {
                                    continue;
                                }
                                
                                $supplierAddress = $supplier
                                    ->addresses()
                                    ->get()
                                    ->first();
                            @endphp

                            <div class="popular-seller-item" style="box-shadow: 7px 2px 6px 1px #E8E8E8;">

                                <div class="profile-information">

                                    <div class="profile-logo-block">
                                        @if ($logo = $supplierAddress->logo_url)
                                            <img src="{{ $logo }}" style="width: 100%; height: 100%;" />
                                        @else
                                            <img src="{{ bagisto_asset('themes/b2b/assets/images/default-logo.svg') }}" />
                                        @endif
                                    </div>

                                    <div class="profile-information-block">
                                        <span class="shop-title">{{ $supplierAddress->company_name }}</span>

                                        @if ($supplierAddress->country)
                                            <label class="shop-address">
                                                {{ $supplierAddress->city . ', ' . $supplierAddress->state . ' (' . core()->country_name($supplierAddress->country) . ')' }}
                                            </label>
                                        @endif

                                        <div class="social-links">
                                            @if ($supplierAddress->facebook)
                                                <a href="https://www.facebook.com/{{ $supplierAddress->facebook }}"
                                                    target="_blank">
                                                    <i class="icon social-icon mp-facebook-icon"></i>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->twitter)
                                                <a href="https://www.twitter.com/{{ $supplierAddress->twitter }}"
                                                    target="_blank">
                                                    <i class="icon social-icon mp-twitter-icon"></i>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->instagram)
                                                <a href="https://www.instagram.com/{{ $supplierAddress->instagram }}"
                                                    target="_blank"><i class="icon social-icon mp-instagram-icon"></i></a>
                                            @endif

                                            @if ($supplierAddress->pinterest)
                                                <a href="https://www.pinterest.com/{{ $supplierAddress->pinterest }}"
                                                    target="_blank"><i class="icon social-icon mp-pinterest-icon"></i></a>
                                            @endif

                                            @if ($supplierAddress->skype)
                                                <a href="https://www.skype.com/{{ $supplierAddress->skype }}"
                                                    target="_blank">
                                                    <i class="icon social-icon mp-skype-icon"></i>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->linked_in)
                                                <a href="https://www.linkedin.com/{{ $supplierAddress->linked_in }}"
                                                    target="_blank">
                                                    <i class="icon social-icon mp-linked-in-icon"></i>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->youtube)
                                                <a href="https://www.youtube.com/{{ $supplierAddress->youtube }}"
                                                    target="_blank">
                                                    <i class="icon social-icon mp-youtube-icon"></i>
                                                </a>
                                            @endif
                                        </div>

                                        <a href="{{ route('b2b_marketplace.supplier.show', $supplier->url) }}"
                                            class="btn btn-lg btn-primary">
                                            {{ __('b2b_marketplace::app.shop.supplier.profile.visit-store') }}
                                        </a>

                                    </div>

                                </div>

                                <?php $popularProducts = app('Webkul\B2BMarketplace\Repositories\ProductRepository')->getPopularProducts($supplier->id); ?>

                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
        @endif

        <div class="about-marketplace-container">

            {!! core()->getConfigData('b2b_marketplace.settings.landing_page.about_b2bmarketplace') !!}

        </div>

        <div class="setup-step-container">
            <div class="setup-heading">
                <h2>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-title') }}</h2>

                <p>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-info') }}</p>
            </div>

            <ul class="setup-step-list">
                <li class="active">
                    <span class="circle">
                        1
                    </span>

                    {{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-1') }}
                </li>

                <li>
                    <span class="circle">
                        2
                    </span>

                    {{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-2') }}
                </li>

                <li>
                    <span class="circle">
                        3
                    </span>

                    {{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-3') }}
                </li>

                <li>
                    <span class="circle">
                        4
                    </span>

                    {{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-4') }}
                </li>

                <li>
                    <span class="circle">
                        5
                    </span>

                    {{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-5') }}
                </li>
            </ul>

        </div>

        @if (core()->getConfigData('b2b_marketplace.settings.landing_page.show_open_shop_block'))
            <div class="open-shop-information-container">
                <p class="open-shop-information">
                    {{ core()->getConfigData('b2b_marketplace.settings.landing_page.open_shop_info') ?? __('b2b_marketplace::app.shop.b2b-marketplace.open-shop-info') }}
                </p>

                <a target="new" href="{{ route('b2b_marketplace.shop.suppliers.signup.index') }}"
                    class="btn btn-black btn-lg">
                    {{ __('b2b_marketplace::app.shop.b2b-marketplace.open-shop-label') }}
                </a>
            </div>
        @endif

    </div>

@endsection
