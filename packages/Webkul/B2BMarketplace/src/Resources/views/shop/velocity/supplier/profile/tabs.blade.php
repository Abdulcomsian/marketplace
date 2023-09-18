<div class="tab" id="main-tab">
    <div class="tab-container">
        <div class="sticky-tabs-default" id="main-link">
            <a class="tablinks" href="#main-tab" id="overview-active" onclick="openProductTab(event, 'Overview')">
                {{ __('b2b_marketplace::app.shop.products.products') }}
            </a>

            <a class="tablinks" href="#about-us" onclick="openProductTab(event, 'Aboutus')">
                {{ __('b2b_marketplace::app.shop.supplier.profile.about-company') }}
            </a>

            <a class="tablinks" id="quick-order" href="#quick-order" onclick="openProductTab(event, 'QuickOrder')">
                {{ __('b2b_marketplace::app.shop.supplier.profile.quick-order') }}
            </a>

            @if (Auth::guard('customer')->check())
                <a class="tablinks" href="#RFQ" id="supplier-active" onclick="openProductTab(event, 'RFQ')">
                    {{ __('b2b_marketplace::app.shop.supplier.profile.rfq') }}
                </a>
            @else
                <a class="tablinks" href="{{route('customer.session.index')}}" id="supplier-active" onclick="openProductTab(event, 'RFQ')">
                    {{ __('b2b_marketplace::app.shop.supplier.profile.rfq') }}
                </a>
            @endif
            <a class="tablinks" href="#reviews" onclick="openProductTab(event, 'Reviews')">
                {{ __('b2b_marketplace::app.shop.products.review') }}
            </a>

            <a class="tablinks" href="#policies" id="supplier-active" onclick="openProductTab(event, 'Policies')">
                {{ __('b2b_marketplace::app.shop.supplier.profile.policies') }}
            </a>

            <a class="tablinks" href="#contact" id="supplier-active" onclick="openProductTab(event, 'Contact')">
                {{ __('b2b_marketplace::app.shop.supplier.profile.contact') }}
            </a>
        </div>
    </div>
</div>

<div id="Overview" class="tabcontent">

    @include('b2b_marketplace::shop.supplier.profile.popular-products')

    @include('b2b_marketplace::shop.supplier.profile.review')

    @include('b2b_marketplace::shop.supplier.profile.about-company')

</div>



<div id="Contact" class="tabcontent">
    <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.profile.about-supplier') }}'" :active="true" id="contact">
        <div slot="header">
            {{ __('b2b_marketplace::app.shop.supplier.profile.about-supplier') }}
            <i class="icon expand-icon right"></i>
        </div>

        <div slot="body">
            <div class="about-supplier">
                <div class="supplier-info">

                    <div class="supplier-header-txt">
                        <h2>
                            {{ __('b2b_marketplace::app.shop.supplier.profile.supplier-info') }}
                        </h2>
                    </div>

                    <div style="display: block;">
                        <div class="supplier-info-container">
                            <a class="aboutus-minilogo">
                                <img class="icon b2bcustomer-icon active" style="margin: -1px -1px;" >
                            </a>

                            <div class="supplier-info-text-container supplier-details" style="">
                                <div class="supplier-info-row" style="width: 100%;
                                display: inline-block;">{{$supplier->first_name . ' ' . $supplier->last_name }}</div>
                                <div class="aboutus-header-text">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.email') }}
                                    {{$supplier->email}}
                                </div>
                                <div class="wk-supplier-collection-header-txt">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.phone-no') }}
                                    {{$supplier->addresses->phone}}</div>
                            </div>

                            <div class="member-since">
                                <div class="supplier-info-row">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.member-since') }}
                                </div>

                                <div class="supplier-details">
                                    {{core()->formatDate($supplier->created_at, 'Y')}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info">
                        <div class="info">
                            <span>
                                {{ __('b2b_marketplace::app.shop.supplier.profile.address') }}
                            </span>

                            <span>{{$supplierAddress->address1}}, {{$supplierAddress->address2}}</span>
                        </div>

                        <div class="info">
                            <span>
                                {{ __('b2b_marketplace::app.shop.supplier.profile.city') }}
                            </span>

                            <span>{{$supplierAddress->city}}</span>
                        </div>

                        <div class="info">
                            <span>{{ __('b2b_marketplace::app.shop.supplier.profile.state') }}</span>
                            <span>{{$supplierAddress->state}}</span>
                        </div>

                        <div class="info">
                            <span>{{ __('b2b_marketplace::app.shop.supplier.profile.post-code') }}</span>
                            <span>{{$supplierAddress->postcode}}</span>
                        </div>

                        <div class="info">
                            <span>{{ __('b2b_marketplace::app.shop.supplier.profile.country') }}</span>
                            <span>{{$supplierAddress->country}}</span>
                        </div>
                    </div>

                    @if (Auth::guard('customer')->check())
                        <div class="profile button" style="padding: 5px; display: inline-flex;">
                            <a id="shopMessage"class="theme-btn" @click="showModal('shopMessage')" style="margin-right: 5px;">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier') }}
                            </a>

                            <a id="quick-order" class="theme-btn tablinks" href="#quick-order" onclick="openQuickOrder('QuickOrder')" style="margin-right: 5px;">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.quick-order') }}
                            </a>

                            <a id="RFQuote" class="theme-btn" href="#RFQ" onclick="RFQuote('RFQ')" style="margin-right: 5px;">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.rfq') }}
                            </a>
                        </div>
                    @else
                        <div class="profile button" style="padding: 5px;display:inline-flex">
                            <a id="shopMessage"class="theme-btn" href="{{route('customer.session.index')}}" style="margin-right: 5px;">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier') }}
                            </a>

                            <a id="quick-order" class="theme-btn tablinks" href="#quick-order" onclick="openQuickOrder('QuickOrder')" style="margin-right: 5px;">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.quick-order') }}
                            </a>

                            <a id="RFQuote" class="theme-btn" href="{{route('customer.session.index')}}" style="margin-right: 5px;">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.rfq') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </accordian>
</div>

<div id="Aboutus" class="tabcontent">
</div>

<div id="Reviews" class="tabcontent">
</div>

<div id="Policies" class="tabcontent">
</div>

<div id="QuickOrder" class="tabcontent">

    @include('b2b_marketplace::shop.supplier.profile.quick-order')

</div>

<div id="RFQ" class="tabcontent">

    @if (Auth::guard('customer')->check())
        @include('b2b_marketplace::shop.supplier.profile.rfq')
    @endif
</div>

@push('scripts')
    <script type="text/javascript">
        window.onload = function() {
            $(this).scrollTop(0);

            let hashRoute = '';
            switch (window.location.hash) {
                case '#quick-order':
                    hashRoute = 'QuickOrder';

                    document.getElementById('quick-order').classList.add('active');
                    var Iscart =1;
                    break;
                default:
                    hashRoute = 'Overview';
                    break;
            }

            openProductTab(event, hashRoute, Iscart);
        };

        function openProductTab(evt, tabName ,Iscart) {

            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");

            for (i = 0; i < tabcontent.length; i++) {

                if (tabcontent[i].id == 'RFQ' && tabName != 'RFQ') {

                        tabcontent[i].style.display = "none";
                } else {
                    if (tabcontent[i].id == 'RFQ' && tabName == 'RFQ') {

                        tabcontent[i].style.display = "block";
                    } else {
                        tabcontent[i].style.display = "none";
                    }
                }

                if (tabName != 'RFQ' && tabcontent[i].id != 'RFQ' && tabName != 'QuickOrder' && tabcontent[i].id != 'QuickOrder') {
                    tabcontent[i].style.display = "block";
                }
            }

            tablinks = document.getElementsByClassName("tablinks");

            for (i = 0; i < tablinks.length; i++) {
                if(Iscart != 1)
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }


            document.getElementById(tabName).style.display = "block";
            document.getElementById(tabName).classList.add('active');

            evt.currentTarget.className += " active";
        }
    </script>

    <script>
        eventBus.$on('configurable-variant-selected-event', function(variantId) {
            var nonVisible = document.getElementsByClassName('productTabs');
            var visible = document.getElementsByClassName(variantId);

            for (let i=0; i < nonVisible.length; i++) {
                nonVisible[i].style.display = "none";
            }

            for (let i=0; i < visible.length; i++) {
                visible[i].style.display = "block";
            }
        });

    </script>
@endpush