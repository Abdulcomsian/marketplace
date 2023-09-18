{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}
@if (core()->getConfigData('b2b_marketplace.settings.general.status'))

    <login-header></login-header>
    <supplier-login-header></supplier-login-header>

    <div class="dropdown">
        <div id="account">
            @if (auth()->guard('customer')->user() == null)
                <ul type="none" style="margin-left: 32%;">
                    <li class="sell-button">
                        <a href="{{ route('b2b_marketplace.supplier_central.index') }}">
                            {{ __('b2b_marketplace::app.shop.supplier.layouts.sell') }}

                        </a>
                    </li>
                </ul>
            @endif

            @if (Auth::guard('customer')->check())
                <ul type="none">
                    <li class="sell-button">
                        <a style="color:#242424" href="{{ route('b2b_marketplace.shop.customers.rfq.index') }}">
                            <img class="logo" src="{{ asset('themes/b2b/assets/images/velocity-Icon-RFQ.svg') }}"
                                alt="" style="margin-bottom: 2px;" height="20" width="20" />
                            {{ __('b2b_marketplace::app.shop.supplier.layouts.quote') }}
                        </a>
                    </li>
                </ul>
            @endif
        </div>
    </div>

    @push('scripts')
        <script type="text/x-template" id="login-header-template">
                <div class="dropdown">
                    <div id="account">

                        <div class="welcome-content pull-right" @click="logintogglePopup">
                            <i class="material-icons align-vertical-top">perm_identity</i>
                            <span class="text-center">
                                @guest('customer')
                                    {{ __('velocity::app.header.welcome-message', ['customer_name' => trans('velocity::app.header.guest')]) }}!
                                @endguest

                                @auth('customer')
                                    {{ __('velocity::app.header.welcome-message', ['customer_name' => auth()->guard('customer')->user()->first_name]) }}
                                @endauth
                            </span>
                            <span class="select-icon rango-arrow-down"></span>
                        </div>
                    </div>

                    <div class="account-modal b2b-login-modal sensitive-modal mt5" id="login-modal" style="display:none;">
                        <!--Content-->
                            @guest('customer')
                                <div class="modal-content">
                                    <!--Header-->
                                    <div class="modal-header no-border pb0">
                                        <label class="fs18 grey">{{ __('shop::app.header.title') }}</label>

                                        <button type="button" class="close disable-box-shadow" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="white-text fs20" @click="logintogglePopup">×</span>
                                        </button>
                                    </div>

                                    <!--Body-->
                                    <div class="pl10 fs14">
                                        <p>{{ __('shop::app.header.dropdown-text') }}</p>
                                    </div>

                                    <!--Footer-->
                                    <div class="modal-footer">
                                        <div>
                                            <a href="{{ route('customer.session.index') }}">
                                                <button
                                                    type="button"
                                                    class="theme-btn fs14 fw6">

                                                    {{ __('shop::app.header.sign-in') }}
                                                </button>
                                            </a>
                                        </div>

                                        <div>
                                            <a href="{{ route('customer.register.index') }}">
                                                <button
                                                    type="button"
                                                    class="theme-btn fs14 fw6">
                                                    {{ __('shop::app.header.sign-up') }}
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endguest

                            @auth('customer')
                                <div class="modal-content customer-options">
                                    <div class="customer-session">
                                        <label class="">
                                            {{ auth()->guard('customer')->user()->first_name }}
                                        </label>
                                    </div>

                                    <ul type="none">
                                        <li>
                                            <a href="{{ route('customer.profile.index') }}" class="unset">{{ __('shop::app.header.profile') }}</a>
                                        </li>

                                        <li>
                                            <a href="{{ route('customer.orders.index') }}" class="unset">{{ __('velocity::app.shop.general.orders') }}</a>
                                        </li>

                                        @php
                                            $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;

                                            $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                                        @endphp

                                        @if ($showWishlist)
                                            <li>
                                                <a href="{{ route('customer.wishlist.index') }}" class="unset">{{ __('shop::app.header.wishlist') }}</a>
                                            </li>
                                        @endif

                                        @if ($showCompare)
                                            <li>
                                                <a href="{{ route('velocity.customer.product.compare') }}" class="unset">{{ __('velocity::app.customer.compare.text') }}</a>
                                            </li>
                                        @endif

                                        <li>
                                            <form id="customerLogout" action="{{ route('customer.session.destroy') }}" method="POST">
                                                @csrf

                                                @method('DELETE')
                                            </form>

                                            <a
                                                class="unset"
                                                href="{{ route('customer.session.destroy') }}"
                                                onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                                                {{ __('shop::app.header.logout') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endauth
                        <!--/.Content-->
                    </div>
                </div>
            </script>

        <script type="text/x-template" id="supplier-login-header-template">
                <div class="dropdown">
                    @guest('customer')
                        <div id="account">

                            <div class="welcome-content pull-right" @click="b2blogintogglePopup">

                                <i class="material-icons align-vertical-top">perm_identity</i>
                                <span class="text-center">
                                        {{ __('Supplier Login') }}
                                </span>
                                <span class="select-icon rango-arrow-down"></span>
                            </div>

                        </div>
                    @endguest
                    
                    @guest('customer')
                        <div class="account-modal b2b-login-modal sensitive-modal mt5" id="b2b-login-modal" style="display:none;">
                            <!--Content-->

                                    <div class="modal-content">
                                        <!--Header-->
                                        <div class="modal-header no-border pb0">
                                            <label class="fs18 grey">{{ __('shop::app.header.title') }}</label>

                                            <button type="button" class="close disable-box-shadow" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" class="white-text fs20" @click="b2blogintogglePopup">×</span>
                                            </button>
                                        </div>

                                        <!--Body-->
                                        <div class="pl10 fs14">
                                            <p>{{ __('Manage Supplier') }}</p>
                                        </div>

                                        <!--Footer-->
                                        <div class="modal-footer">
                                            <div>
                                                <a href="{{ route('b2b_marketplace.shop.supplier.session.index') }}">
                                                    <button
                                                        type="button"
                                                        class="theme-btn fs14 fw6">

                                                        {{ __('shop::app.header.sign-in') }}
                                                    </button>
                                                </a>
                                            </div>

                                            <div>
                                                <a href="{{ route('b2b_marketplace.shop.suppliers.signup.index') }}">
                                                    <button
                                                        type="button"
                                                        class="theme-btn fs14 fw6">
                                                        {{ __('shop::app.header.sign-up') }}
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>


                            <!--/.Content-->
                        </div>
                    @endguest
                </div>
            </script>

        <script type="text/javascript">
            Vue.component('login-header', {
                template: '#login-header-template',

                methods: {
                    togglePopup: function(event) {
                        let accountModal = this.$el.querySelector('.account-modal');
                        let modal = $('#cart-modal-content')[0];

                        if (modal)
                            modal.classList.add('hide');

                        accountModal.classList.toggle('hide');

                        event.stopPropagation();
                    }
                }
            })
        </script>

        <script type="text/javascript">
            Vue.component('supplier-login-header', {
                template: '#supplier-login-header-template',

                methods: {
                    togglePopup: function(event) {
                        let accountModal = this.$el.querySelector('.account-modal');

                        let modal = $('#cart-modal-content')[0];

                        if (modal)
                            modal.classList.add('hide');

                        accountModal.classList.toggle('hide');

                        event.stopPropagation();
                    }
                }
            })
        </script>

        <script type="text/javascript">
            function logintogglePopup() {

                var login = document.getElementById("login-modal");
                var b2blogin = document.getElementById("b2b-login-modal");

                if (login.style.display === "none") {
                    login.style.display = "block";
                    b2blogin.style.display = "none";
                } else {
                    login.style.display = "none";
                }
            }

            function b2blogintogglePopup() {

                var login = document.getElementById("login-modal");
                var b2blogin = document.getElementById("b2b-login-modal");

                if (b2blogin.style.display === "none") {
                    b2blogin.style.display = "block";
                    login.style.display = "none";
                } else {
                    b2blogin.style.display = "none";
                }
            }
        </script>
    @endpush
@else
    <div id="account">
        <div class="d-inline-block welcome-content dropdown-toggle">
            <i class="material-icons align-vertical-top">perm_identity</i>

            <span class="text-center">
                {{ __('velocity::app.header.welcome-message', [
                    'customer_name' => auth()->guard('customer')->user()
                        ? auth()->guard('customer')->user()->first_name
                        : trans('velocity::app.header.guest'),
                ]) }}
            </span>

            <span class="rango-arrow-down"></span>
        </div>

        @guest('customer')
            <div class="dropdown-list" style="width: 290px">
                <div class="modal-content dropdown-container">
                    <div class="modal-header no-border pb0">
                        <label class="fs18 grey">{{ __('shop::app.header.title') }}</label>
                    </div>

                    <div class="fs14 content">
                        <p class="no-margin">{{ __('shop::app.header.dropdown-text') }}</p>
                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('customer.session.index') }}" class="theme-btn fs14 fw6">
                            {{ __('shop::app.header.sign-in') }}
                        </a>

                        <a href="{{ route('customer.register.index') }}" class="theme-btn fs14 fw6">
                            {{ __('shop::app.header.sign-up') }}
                        </a>
                    </div>
                </div>
            </div>
        @endguest

        @auth('customer')
            <div class="dropdown-list">
                <div class="dropdown-label">
                    {{ auth()->guard('customer')->user()->first_name }}
                </div>

                <div class="dropdown-container">
                    <ul type="none">
                        <li>
                            <a href="{{ route('customer.profile.index') }}"
                                class="unset">{{ __('shop::app.header.profile') }}</a>
                        </li>

                        <li>
                            <a href="{{ route('customer.orders.index') }}"
                                class="unset">{{ __('velocity::app.shop.general.orders') }}</a>
                        </li>

                        @php
                            $showCompare = core()->getConfigData('general.content.shop.compare_option') == '1' ? true : false;
                            
                            $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == '1' ? true : false;
                        @endphp

                        @if ($showWishlist)
                            <li>
                                <a href="{{ route('customer.wishlist.index') }}"
                                    class="unset">{{ __('shop::app.header.wishlist') }}</a>
                            </li>
                        @endif

                        @if ($showCompare)
                            <li>
                                <a href="{{ route('velocity.customer.product.compare') }}"
                                    class="unset">{{ __('velocity::app.customer.compare.text') }}</a>
                            </li>
                        @endif

                        <li>
                            <form id="customerLogout" action="{{ route('customer.session.destroy') }}" method="POST">
                                @csrf

                                @method('DELETE')
                            </form>

                            <a class="unset" href="{{ route('customer.session.destroy') }}"
                                onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                                {{ __('shop::app.header.logout') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </div>
@endif
{!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}
