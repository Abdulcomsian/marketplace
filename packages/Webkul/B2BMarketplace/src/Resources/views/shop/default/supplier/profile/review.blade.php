<div class="profile-details">

    <?php $reviews = app('Webkul\B2BMarketplace\Repositories\ReviewRepository')->getRecentReviews($supplier->id);
    ?>
    <div class="profile-details-left-block section" id="reviews">
        @if ($reviews->count())

            <div class="slider-container">
                <div class="slider-content">

                    <carousel :per-page="1" pagination-active-color="#979797" pagination-color="#E8E8E8">
                        @foreach ($reviews as $review)

                            <slide>
                                <span class="stars">
                                    @for ($i = 1; $i <= $review->rating; $i++)

                                        <span class="icon star-icon"></span>

                                    @endfor
                                </span>

                                <p>
                                    {{ $review->comment }}
                                </p>

                                <p>
                                    {{
                                        __('b2b_marketplace::app.shop.supplier.profile.by-user-date', [
                                                'name' => $review->customer->name,
                                                'date' => core()->formatDate($review->created_at, 'F d, Y')
                                            ])
                                    }}
                                </p>
                            </slide>

                        @endforeach
                    </carousel>

                </div>

                <a href="{{ route('b2b_marketplace.reviews.index', $supplier->url) }}">{{ __('b2b_marketplace::app.shop.supplier.profile.all-reviews') }}</a>
            </div>

        @endif
    </div>

    @if ($supplier->addresses->address1
        && core()->getConfigData('b2b_marketplace.settings.supplier_profile_page.policies_enable') )

        <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.profile.return-policy') }}'" :active="true" id="policies">
            <div slot="header">
                {{ __('b2b_marketplace::app.shop.supplier.profile.return-policy') }}
                <i class="icon expand-icon right"></i>
            </div>

            <div slot="body">
                <div class="full-description">
                    {!! $supplier->addresses->return_policy !!}
                </div>
            </div>
        </accordian>

        <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.profile.shipping-policy') }}'" :active="true">
            <div slot="header">
                {{ __('b2b_marketplace::app.shop.supplier.profile.shipping-policy') }}
                <i class="icon expand-icon right"></i>
            </div>

            <div slot="body">
                <div class="full-description">
                    {!! $supplier->addresses->shipping_policy !!}
                </div>
            </div>
        </accordian>

        <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.profile.privacy_policy') }}'" :active="true">
            <div slot="header">
                {{ __('b2b_marketplace::app.shop.supplier.profile.privacy_policy') }}
                <i class="icon expand-icon right"></i>
            </div>

            <div slot="body">
                <div class="full-description">
                    {!! $supplier->addresses->privacy_policy !!}
                </div>
            </div>
        </accordian>
    @endif

    @if ($supplier->addresses->address1)

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

                                <div class="supplier-info-text-container supplier-details">
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
                            <div class="profile button" style="padding: 5px;">
                                <a id="shopMessage"class="btn btn-lg btn-primary" @click="showModal('shopMessage')">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier') }}
                                </a>

                                <a id="quick-order" class="btn btn-lg btn-primary tablinks" href="#quick-order" onclick="openQuickOrder('QuickOrder')">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.quick-order') }}
                                </a>

                                <a id="RFQuote" class="btn btn-lg btn-primary" href="#RFQ" onclick="RFQuote('RFQ')">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.rfq') }}
                                </a>
                            </div>
                        @else
                            <div class="profile button" style="padding: 5px;">
                                <a id="shopMessage"class="btn btn-lg btn-primary" href="{{route('customer.session.index')}}">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier') }}
                                </a>

                                <a id="quick-order" class="btn btn-lg btn-primary tablinks" href="#quick-order" onclick="openQuickOrder('QuickOrder')">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.quick-order') }}
                                </a>

                                <a id="RFQuote" class="btn btn-lg btn-primary" href="{{route('customer.session.index')}}">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.rfq') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </accordian>

    @endif
</div>

@push('scripts')
    <script type="text/javascript">
        function openQuickOrder(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");

            for (i = 0; i < tabcontent.length; i++) {

                if (tabcontent[i].id == 'QuickOrder') {

                    tabcontent[i].style.display = "block";
                } else {
                    tabcontent[i].style.display = "none";
                }
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {

                if (tablinks[i].id == 'quick-order') {
                    $("#quick-order").addClass("active");
                } else {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
            }
        }

        function RFQuote(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");

            for (i = 0; i < tabcontent.length; i++) {

                if (tabcontent[i].id == 'RFQ') {

                    tabcontent[i].style.display = "block";
                } else {
                    tabcontent[i].style.display = "none";
                }
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {

                if (tablinks[i].id == 'supplier-active') {
                    $("#supplier-active").addClass("active");
                } else {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
            }
        }
    </script>
@endpush