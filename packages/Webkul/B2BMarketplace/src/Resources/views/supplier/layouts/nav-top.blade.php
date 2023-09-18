<div class="navbar-top">
    <div class="navbar-top-left">
        @include ('b2b_marketplace::supplier.layouts.mobile-nav')
        <div class="brand-logo">
            <a href="{{ route('b2b_marketplace.supplier.dashboard.index') }}">
                @if (core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode()))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode())) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
                @else
                    <default-image
                        light-theme-image-url="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}"
                        dark-theme-image-url="{{ asset('vendor/webkul/ui/assets/images/logo_light.png') }}"
                    ></default-image>
                @endif
            </a>
        </div>
    </div>

    <div class="navbar-top-right">
        <div class="profile">
            <span class="avatar">
            </span>

            <div class="store">
                <div>
                    <a  href="{{ route('shop.home.index') }}" target="_blank" style="display: inline-block; vertical-align: middle;">
                        <span class="icon store-icon" data-toggle="tooltip" data-placement="bottom" title="{{ __('admin::app.layouts.visit-shop') }}"></span>
                    </a>
                </div>
            </div>

            <div class="profile-info">
                <div class="dropdown-toggle">
                    <div style="display: inline-block; vertical-align: middle;">
                        <div class="profile-info-div">
                            @if (auth()->guard('supplier')->user()->addresses->profile_url() != '')
                                <div class="profile-info-icon">
                                    <img src="{{auth()->guard('supplier')->user()->addresses->profile_url()}}"/>
                                </div>
                            @else
                                <div class="profile-info-icon">
                                    <span>{{ substr(auth()->guard('supplier')->user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="profile-info-desc">
                                <span class="name">
                                    {{ auth()->guard('supplier')->user()->name }}
                                </span>

                                <span class="role">
                                    {{__('Supplier')}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <i class="icon arrow-down-icon active"></i>
                </div>

                <div class="dropdown-list bottom-right">
                    <span class="app-version">{{ __('admin::app.layouts.app-version', ['version' => 'v' . core()->version()]) }}</span>

                    <div class="dropdown-container">
                        <label>{{ __('admin::app.layouts.account-title') }}</label>
                        <ul>
                            <li>
                                <a href="{{ route('b2b_marketplace.supplier.settings.index') }}">{{ __('admin::app.layouts.my-account') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('b2b_marketplace.session.destroy') }}">{{ __('admin::app.layouts.logout') }}</a>
                            </li>
                            <li v-if="!isMobile()" style="display: flex;justify-content: space-between;">
                                <div style="margin-top:7px">{{ __('admin::app.layouts.mode') }}</div>
                                <dark style="margin-top: -9px;width: 83px;"></dark>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>