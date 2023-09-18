@extends('shop::layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('themes/b2b/assets/css/b2b-marketplace.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/b2b/assets/css/b2b-marketplace-shop.css') }}">

@endpush

@push('scripts')

    <script type="text/javascript" src="{{ asset('themes/b2b/assets/js/b2b-marketplace.js') }}"></script>

    <style type="text/css">

        .tabs ul li .message-unseen-count {
            background: #f82e56;
            color: #fff;
            font-size: 11px;
            font-weight: 500;
            padding: 0px 7px;
            border-radius: 12px;
        }

        .rfq-btn {

            display: block !important;
            text-transform: uppercase !important;

        }

    </style>
@endpush