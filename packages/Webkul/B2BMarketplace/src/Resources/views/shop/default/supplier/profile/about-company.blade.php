<div class="about-company" id="about-us">
    @if ($supplier->addresses->address1)
        <h2 class="wk-supplier-collection-h2">
            {{ __('b2b_marketplace::app.shop.supplier.profile.about-company')}}
        </h2>

        <div class="supplier-aboutus-title">
            <div>
                <a>
                    <h3>{{$supplier->addresses->company_name}}</h3>
                </a>
            </div>

            <div class="aboutus-header-txt">{{$supplier->addresses->company_tag_line}}</div>
        </div>

        <div class="aboutus-container aboutus-header-txt">
            <div class="supplier-aboutus-row">
                {{ __('b2b_marketplace::app.shop.supplier.profile.registered-in')}}
                <strong>
                    {{$supplier->addresses->registerd_in}}
                </strong>
            </div>

            <div class="supplier-aboutus-row">
                {{ __('b2b_marketplace::app.shop.supplier.profile.company-team-size')}}
                <strong>
                    {{$supplier->addresses->team_size}}
                </strong>
            </div>

            <div class="supplier-aboutus-row">
                {{ __('b2b_marketplace::app.shop.supplier.profile.certification')}}
                <strong>
                    {{$supplier->addresses->certification}}
                </strong>
            </div>
        </div>

        <div class="aboutus-container aboutus-header-txt">
            <div class="aboutus-container-left">
                <div>
                    {{ __('b2b_marketplace::app.shop.supplier.profile.operational-address')}}
                </div>

                <div>
                    {{$supplier->first_name . ' '. $supplier->last_name}}
                    <br>{{$supplier->addresses->company_name}}<br>
                    {{$supplier->addresses->address1 . ', '. $supplier->addresses->city . ', ' . $supplier->addresses->state . ', ' . $supplier->addresses->postcode}}
                    <br>{{$supplier->addresses->country}}<br>
                    {{ __('b2b_marketplace::app.shop.supplier.profile.phone')}}
                    <a>{{$supplier->addresses->phone}}</a>
                </div>
            </div>

            <div class="aboutus-container-left">
                <div>
                    {{ __('b2b_marketplace::app.shop.supplier.profile.corporate-address')}}
                </div>

                <div>
                    {{$supplier->first_name . ' '. $supplier->last_name}}<br>
                    {{$supplier->addresses->company_name}}<br>{{$supplier->addresses->corporate_address1 . ', '. $supplier->addresses->corporate_city . ', ' . $supplier->addresses->corporate_state . ', ' . $supplier->addresses->corporate_postcode}}<br>{{$supplier->addresses->corporate_country}}<br>
                    {{ __('b2b_marketplace::app.shop.supplier.profile.phone')}}
                    <a>{{$supplier->addresses->corporate_phone}}</a>
                </div>
            </div>
        </div>

        <h3>
            {{ __('b2b_marketplace::app.shop.supplier.profile.overview')}}
        </h3>

        <div class="supplier-aboutus-data">
            <p class="detail-aboutus">
                {!! $supplier->addresses->company_overview !!}
            </p>
        </div>

        <h3>
            {{ __('b2b_marketplace::app.shop.supplier.profile.social-channels')}}
        </h3>
    @endif
    <div class="social-links">
        @if ($supplierAddress->facebook)
        <a href="https://www.facebook.com/{{$supplierAddress->facebook}}" target="_blank">
            <i class="icon social-icon mp-facebook-icon"></i>
        </a>
        @endif

        @if ($supplierAddress->twitter)
            <a href="https://www.twitter.com/{{$supplierAddress->twitter}}" target="_blank">
                <i class="icon social-icon mp-twitter-icon"></i>
            </a>
        @endif

        @if ($supplierAddress->instagram)
            <a href="https://www.instagram.com/{{$supplierAddress->instagram}}" target="_blank"><i class="icon social-icon mp-instagram-icon"></i></a>
        @endif

        @if ($supplierAddress->pinterest)
            <a href="https://www.pinterest.com/{{$supplierAddress->pinterest}}" target="_blank"><i class="icon social-icon mp-pinterest-icon"></i></a>
        @endif

        @if ($supplierAddress->skype)
            <a href="https://www.skype.com/{{$supplierAddress->skype}}" target="_blank">
                <i class="icon social-icon mp-skype-icon"></i>
            </a>
        @endif

        @if ($supplierAddress->linked_in)
            <a href="https://www.linkedin.com/{{$supplierAddress->linked_in}}" target="_blank">
                <i class="icon social-icon mp-linked-in-icon"></i>
            </a>
        @endif

        @if ($supplierAddress->youtube)
            <a href="https://www.youtube.com/{{$supplierAddress->youtube}}" target="_blank">
                <i class="icon social-icon mp-youtube-icon"></i>
            </a>
        @endif
    </div>
</div>
