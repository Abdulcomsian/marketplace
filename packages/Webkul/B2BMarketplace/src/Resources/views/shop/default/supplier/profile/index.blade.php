@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ $supplier->shop_title }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($supplier->meta_description) != "" ? $supplier->meta_description : \Illuminate\Support\Str::limit(strip_tags($supplier->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $supplier->meta_keywords }}"/>
@stop

@section('content-wrapper')

    <div class="main">
        <?php $supplierAddress = $supplier->addresses()->get()->first();?>
        <div class="profile-container">

            <div class="profile-right-block">

                @if ($banner = $supplierAddress->banner_url)
                    <img src="{{ $banner }}" />
                @else
                    <img src="{{ bagisto_asset('themes/b2b/assets/images/default-banner.svg') }}" />
                @endif

            </div>

            @include('b2b_marketplace::shop.supplier.profile.left-profile')

        </div>

        @include('b2b_marketplace::shop.supplier.profile.tabs')
    </div>

@endsection