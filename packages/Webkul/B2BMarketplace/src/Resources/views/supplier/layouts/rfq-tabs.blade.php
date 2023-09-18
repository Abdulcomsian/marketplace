@extends('b2b_marketplace::supplier.layouts.master')

@section('content-wrapper')
    <div class="inner-section">

        {{-- <div class="mp-page-header">
            <div class="page-title-wrapper">
                <h1 title="page-title">
                    <span>Quotes</span>
                </h1>
            </div>
        </div> --}}

        {{-- <div class="content-wrapper" style="padding: 25px; margin-left: 20px;"> --}}

            {{-- <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Request For Quotes') }}</h1>
                </div>

                <div class="page-action">
                </div>
            </div> --}}

            <div class="content-wrapper">

            {{-- @include ('b2b_marketplace::supplier.layouts.tabs') --}}

            @yield('content')

        </div>
    </div>
@stop