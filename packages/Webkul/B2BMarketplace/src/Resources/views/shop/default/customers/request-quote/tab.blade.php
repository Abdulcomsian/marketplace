@extends('b2b_marketplace::shop.layouts.account')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.supplier-response') }}
@endsection

@php
    $allDataGrid = [
        'new' => app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\NewDataGrid'),
        'pending' => app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\PendingDataGrid'),
        'answered' => app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\AnsweredDataGrid'),
        'confirmed' => app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\ConfirmedDataGrid'),
        'rejected' => app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\RejectedDataGrid')
    ];

    if(isset($status)) {
        $status = $status;
    } else {
        $status = 'new';
    }

    $allStatus = [
        'new',
        'pending',
        'answered',
        'confirmed',
        'rejected'
    ];
@endphp

@section('content')
<div class="account-layout">

    <div class="account-head mb-10">
        <span class="account-heading">
            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.supplier-response') }}
        </span>

        <div class="horizontal-rule"></div>
    </div>

    <div class="sale-container">
        <div>

            <div class="tabs">
                <ul>
                    @foreach ($allStatus as $rfqStatus)
                        <li @if($status == $rfqStatus && in_array($status, $allStatus)) class="active" @endif style="box-shadow: 0px 3px 6px 0px #c4c4c4;">
                            @if ($supplierQuote != null)
                                <a href="{{route('b2b_marketplace.supplier.request-quote.status', [
                                    $rfqStatus,
                                    $supplierQuote->quote_id,
                                    $supplierQuote->product_id

                                ])}}" >

                                {{ucfirst($rfqStatus)}}

                                @foreach ($allDataGrid as $key => $gridCount)

                                    @if($key == $rfqStatus && $gridCount->export()->count() > 0)
                                        <span class="message-unseen-count">
                                            {{$gridCount->export()->count()}}
                                        </span>
                                    @endif
                                @endforeach

                            </a>

                            @else
                                <a href="">{{ __('b2b_marketplace::app.shop.supplier.account.rfq.new') }}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
                
            </div>
        </div>
    </div>

    {!! view_render_event('b2b_marketplace.customer.account.rfq.list.before') !!}

        <div class="tabs-content" style="padding-top: 10px;">
            <div class="account-items-list">
                @if($status == 'new')
                    {!! app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\NewDataGrid')->render() !!}
                @elseif($status == 'pending')
                    {!! app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\PendingDataGrid')->render() !!}
                @elseif($status == 'answered')
                    {!! app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\AnsweredDataGrid')->render() !!}
                @elseif($status == 'confirmed')
                    {!! app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\ConfirmedDataGrid')->render() !!}
                @elseif($status == 'rejected')
                    {!! app('Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus\RejectedDataGrid')->render() !!}
                @endif
            </div>
        </div>

    {!! view_render_event('b2b_marketplace.customer.account.rfq.list.after') !!}
</div>

@endsection