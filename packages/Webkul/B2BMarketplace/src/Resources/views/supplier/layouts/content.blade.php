@extends('b2b_marketplace::supplier.layouts.master')

@section('content-wrapper')
    <div class="inner-section">

        <div class="content-wrapper">

            @include ('b2b_marketplace::supplier.layouts.tabs')

            @yield('content')

        </div>
    </div>
@stop