@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.verification.title') }}
@stop

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>{{ __('b2b_marketplace::app.shop.supplier.verification.title') }}</h1>
        </div>
    </div>

    <div class="page-content">

        @if (! $supplier->is_verified)
            <div class="verification-container" style="
                    margin: 10px;
                    font-size: 16px;
                    border: 2px solid #e8e8e8;
                    border-radius: 1rem;
                    padding: 20px;
                    ">
                <div class="fieldset">
                    <div class="supplier-verification" style="
                            display: inline-block;
                            width: 100%;
                            box-sizing: border-box;
                            padding: 10px;
                            background: #FFFBD4;
                            margin-bottom: 15px;
                            border-radius: 2px;
                            border: 1px solid #E9DF80;
                            color: #333;
                        ">
                        <span>A verification email is sent to</span>
                        <span style="color:#0041ff">{{$supplier->email}}</span>
                        <span>, check your inbox for verification email or</span>

                        <a href="{{route('b2b_marketplace.supplier.verification.resend')}}">click here</a>

                        <span>to resend link.</span>
                    </div>

                    <div class="field" style="padding: 3px;">
                        <div class="main-container" style="margin-bottom: 10px;">
                            <label class="email-label" style="color: black;">
                                <span>Email Address:</span>
                            </label>
                        </div>

                        <div class="control">
                            <div class="mail-container">
                                <span>{{$supplier->email}}</span>
                            </div>

                            <div class="main-container" style="margin-bottom: 10px; color:red">
                                Not Verified

                            </div>

                            <div class="main-container hr-line" style="border-bottom: 2px solid #e8e8e8; margin-bottom: 10px;"></div>

                            <div class="main-container" style="margin-bottom: 10px;">
                                <span>
                                    Once you verify the details above you will get the Verification Badge which will be displayed along with the profile.
                                </span>
                            </div>

                            <div class="main-container" style="display: grid;">
                                <span class="verified-supplier" style="color: darkgray;">Verified supplier</span>
                                <span class="icon verification-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
        <div class="verification-container" style="
        margin: 10px;
        font-size: 16px;
        border: 2px solid #e8e8e8;
        border-radius: 1rem;
        padding: 20px;
        ">
    <div class="fieldset">
        {{-- <div class="supplier-verification" style="
                display: inline-block;
                width: 100%;
                box-sizing: border-box;
                padding: 10px;
                background: #FFFBD4;
                margin-bottom: 15px;
                border-radius: 2px;
                border: 1px solid #E9DF80;
                color: #333;
            ">
            <span>A verification email is sent to
                prateek.srivastava781@webkul.com
                , check your inbox for verification email or
            </span>

        <a href="{{route('b2b_marketplace.supplier.verification.resend')}}">click here</a>

            <span>to resend link.</span>
        </div> --}}

        <div class="field" style="padding: 3px;">
            <div class="main-container" style="margin-bottom: 10px;">
                <label class="email-label" style="color: black;">
                    <span>Email Address:</span>
                </label>
            </div>

            <div class="control">
                <div class="mail-container">
                    <span>{{$supplier->email}}</span>
                </div>

                <div class="main-container" style="margin-bottom: 10px; color:green">
                    Verified
                </div>

                <div class="main-container hr-line" style="border-bottom: 2px solid #e8e8e8; margin-bottom: 10px;"></div>

                <div class="main-container" style="margin-bottom: 10px;">
                    <span>
                        You have earned the Verified Supplier Badge
                    </span>
                </div>

                <div class="main-container" style="display: grid;">
                    <span class="verified-supplier" style="color: green;">Verified supplier</span>
                    <span class="icon verification-icon active"></span>
                </div>
            </div>
        </div>
    </div>
</div>
        @endif
    </div>
{!! view_render_event('b2b_marketplace.supplier.verification.after') !!}
</div>
@endsection