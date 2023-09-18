@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.b2b-marketplace.title') }}
@stop

@section('content-wrapper')
    <div class="main seller-central-container">

        @if (core()->getConfigData('b2b_marketplace.settings.velocity.show_banner'))
            <div class="banner-container">

                @if (core()->getConfigData('b2b_marketplace.settings.velocity.banner'))
                    <img class="banner" src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.banner')) }}"/>
                @else
                    <img class="banner" src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/banner-3.png"/>
                @endif

                <div class="banner-content">
                    <h1>
                        {{ core()->getConfigData('b2b_marketplace.settings.velocity.page_title') ?? __('b2b_marketplace::app.shop.b2b-marketplace.title') }}
                    </h1>

                    @if ($bannerContent = core()->getConfigData('b2b_marketplace.settings.velocity.banner_content'))
                        <p>
                            {!! $bannerContent !!}
                        </p>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.show_open_shop_block'))
                        <div class="account-action">
                            <a target="new" href="{{ route('b2b_marketplace.shop.suppliers.signup.index') }}" class="btn btn-lg theme-btn">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.open_shop_button_label') ?? __('b2b-marketplace::app.shop.b2b-marketplace.open-shop-label') }}
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        @endif

        {{-- b2b features --}}
        @if (core()->getConfigData('b2b_marketplace.settings.velocity.show_features'))
            <div class="feature-container">
                <div class="feature-heading">
                    <h2>{{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_heading') ?? __('b2b_marketplace::app.shop.b2b-marketplace.features') }}</h2>

                    <p>{{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_info') ?? __('b2b_marketplace::app.shop.b2b-marketplace.features-info') }}</p>
                </div>

                <ul type="none" class="feature-list">
                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_1') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_1'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_1')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_1') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_2') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_2'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_2')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_2') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_3') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_3'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_3')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_3') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_4') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_4'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_4')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_4') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_5') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_5'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_5')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_5') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_6') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_6'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_6')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_6') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_7') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_7'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_7')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_7') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_8') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_8'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_8')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_8') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_9') && core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_9'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_9')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('b2b_marketplace.settings.velocity.feature_icon_label_9') }}
                            </div>
                        </li>
                    @endif
                </ul>

            </div>
        @endif

        @if (core()->getConfigData('b2b_marketplace.settings.velocity.show_popular_suppliers'))
            <?php $popularSuppliers = app('Webkul\B2BMarketplace\Repositories\SupplierRepository')->getPopularSellers(); ?>

            @if ($popularSuppliers->count())
                <div class="popular-sellers-container p-4">
                    <div class="popular-sellers-heading text-center">
                        {{ __('b2b_marketplace::app.shop.supplier.profile.popular-supplier') }}
                    </div>

                    <div class="popular-sellers-list">

                        @foreach ($popularSuppliers as $supplier)

                            @php 
                                if ($supplier->is_approved == 0) {
                                    continue;
                                }

                                $supplierAddress = $supplier->addresses()->get()->first();
                            @endphp

                            <div class="popular-seller-item" style="box-shadow: 7px 2px 6px 1px #E8E8E8;">

                                <div class="profile-information">

                                    <div class="profile-logo-block">
                                        @if ($logo = $supplierAddress->logo_url)
                                            <img src="{{ $logo }}" style="width: 100%; height: 100%;"/>
                                        @else
                                            <img src="{{ bagisto_asset('themes/b2b/assets/images/default-logo.svg') }}" />
                                        @endif
                                    </div>

                                    <div class="profile-information-block">
                                        <span class="shop-title">{{ $supplierAddress->company_name }}</span>

                                        @if ($supplierAddress->country)
                                            <label class="shop-address">
                                                {{ $supplierAddress->city . ', '. $supplierAddress->state . ' (' . core()->country_name($supplierAddress->country) . ')' }}
                                            </label>
                                        @endif

                                        <div class="social-links">
                                            @if ($supplierAddress->facebook)
                                                <a href="https://www.facebook.com/{{$supplierAddress->facebook}}" target="_blank">
                                                    <span class="icon social-icon mp-facebook-icon"></span>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->twitter)
                                                <a href="https://www.twitter.com/{{$supplierAddress->twitter}}" target="_blank">
                                                    <span class="icon social-icon mp-twitter-icon"></span>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->instagram)
                                                <a href="https://www.instagram.com/{{$supplierAddress->instagram}}" target="_blank"><span class="icon social-icon mp-instagram-icon"></span></a>
                                            @endif

                                            @if ($supplierAddress->pinterest)
                                                <a href="https://www.pinterest.com/{{$supplierAddress->pinterest}}" target="_blank"><span class="icon social-icon mp-pinterest-icon"></span></a>
                                            @endif

                                            @if ($supplierAddress->skype)
                                                <a href="https://www.skype.com/{{$supplierAddress->skype}}" target="_blank">
                                                    <span class="icon social-icon mp-skype-icon"></span>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->linked_in)
                                                <a href="https://www.linkedin.com/{{$supplierAddress->linked_in}}" target="_blank">
                                                    <span class="icon social-icon mp-linked-in-icon"></span>
                                                </a>
                                            @endif

                                            @if ($supplierAddress->youtube)
                                                <a href="https://www.youtube.com/{{$supplierAddress->youtube}}" target="_blank">
                                                    <span class="icon social-icon mp-youtube-icon"></span>
                                                </a>
                                            @endif
                                        </div>

                                        <?php
                                            $url = explode('.', $supplier->url)[0];
                                        ?>

                                        <div class="popular-btn">
                                            <a href="{{route('b2b_marketplace.supplier.show', $url)}}" class="theme-btn">
                                                {{ __('b2b_marketplace::app.shop.supplier.profile.visit-store') }}
                                            </a>
                                        </div>

                                    </div>

                                </div>

                                <?php $popularProducts = app('Webkul\B2BMarketplace\Repositories\ProductRepository')->getPopularProducts($supplier->id); ?>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
        @endif

        <div class="setup-step-container">
            <div class="setup-heading">
                <h2>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-title') }}</h2>

                <p>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-info') }}</p>
            </div>

            <ul class="velocity-setup-step-list">
                <li>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.setup_icon_1')) }}"/>

                    <span>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-1') }}</span>
                </li>

                <li>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.setup_icon_2')) }}"/>

                    <span>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-2') }}</span>
                </li>

                <li>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.setup_icon_3')) }}"/>

                    <span>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-3') }}</span>
                </li>

                <li>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.setup_icon_4')) }}"/>

                    <span>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-4') }}</span>
                </li>

                <li>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('b2b_marketplace.settings.velocity.setup_icon_5')) }}"/>

                    <span>{{ __('b2b_marketplace::app.shop.b2b-marketplace.setup-5') }}</span>
                </li>
            </ul>
        </div>
    </div>



@endsection