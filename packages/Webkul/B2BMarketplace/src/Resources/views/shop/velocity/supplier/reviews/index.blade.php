@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.reviews.title', ['shop_title' => $supplier->company_name]) }} -
    {{ $supplier->name }}
@endsection

@push('css')
    <style>
        @media only screen and (max-width: 770px) {
            section.review .review-layouter .review-form {
                margin-left: 20px !important;
            }
        }
    </style>
@endpush

@section('content-wrapper')
    <section class="profile-container review">

        {!! view_render_event('b2b_marketplace.shop.suppliers.reviews.index.before', ['supplier' => $supplier]) !!}

        @include('b2b_marketplace::shop.supplier.profile.left-profile')

        <div class="review-layouter profile-right-block" style="margin-top: 3%;">

            <div class="review-form" style="width: 70%;">

                <div class="heading mt-10">
                    <span> {{ __('shop::app.reviews.rating-reviews') }} </span>

                    <a href="{{ route('b2b_marketplace.reviews.create', $supplier->url) }}" class="theme-btn right">

                        {{ __('b2b_marketplace::app.shop.supplier.reviews.write-review') }}
                    </a>
                </div>

                <?php $reviewRepository = app('Webkul\B2BMarketplace\Repositories\ReviewRepository'); ?>

                <div class="ratings-reviews mt-35">

                    <div class="left-side">
                        <span class="rate">
                            {{ $reviewRepository->getAverageRating($supplier) }}
                        </span>

                        @for ($i = 1; $i <= $reviewRepository->getAverageRating($supplier); $i++)
                            <span class="stars">
                                <span class="icon star-icon"></span>
                            </span>
                        @endfor

                        <div class="total-reviews mt-5">
                            {{ __('b2b_marketplace::app.shop.supplier.reviews.total-rating', [
                                'total_rating' => $reviewRepository->getTotalRating($supplier),
                                'total_reviews' => $reviewRepository->getTotalReviews($supplier),
                            ]) }}
                        </div>
                    </div>

                    <div class="right-side">
                        @foreach ($reviewRepository->getPercentageRating($supplier) as $key => $count)
                            <div class="rater 5star">
                                <div class="rate-number" id={{ $key }}star></div>
                                <div class="star-name">Star</div>

                                <div class="line-bar">
                                    <div class="line-value" id="{{ $key }}"></div>
                                </div>

                                <div class="percentage">
                                    <span> {{ $count }}% </span>
                                </div>
                            </div>

                            <br />
                        @endforeach
                    </div>

                </div>

                <div class="rating-reviews">
                    <div class="reviews"
                        style="height: 250px;
                    overflow-x: auto;
                    padding-bottom: 20px;">

                        <?php
                        $page = request()->get('pages') ?? 1;
                        
                        $reviews = $reviewRepository->getReviews($supplier)->paginate(10 * $page);
                        ?>

                        @foreach ($reviews as $review)
                            <div class="review" style="margin-bottom: 10px;">
                                <div class="title">
                                    {{ $review->title }}
                                </div>

                                <span class="stars">
                                    @for ($i = 1; $i <= $review->rating; $i++)
                                        <span class="icon star-icon"></span>
                                    @endfor
                                </span>

                                <div class="message">
                                    {{ $review->comment }}
                                </div>

                                <div class="reviewer-details">
                                    <span class="by">
                                        {!! __('b2b_marketplace::app.shop.supplier.reviews.by-user-date', [
                                            'name' => '<b>'.$review->customer->name.'</b>',
                                            'date' => core()->formatDate($review->created_at, 'F d, Y'),
                                        ]) !!}
                                    </span>
                                </div>
                            </div>  
                        @endforeach

                    </div>

                    @if ($page < $reviews->lastPage())
                        <div class="navigation">

                            <a href="?pages={{ $page + 1 }}">
                                {{ __('b2b_marketplace::app.shop.supplier.reviews.view-more') }}
                            </a>

                        </div>
                    @endif

                </div>

            </div>

        </div>

        {!! view_render_event('b2b_marketplace.shop.supplier.reviews.index.after', ['supplier' => $supplier]) !!}

    </section>
@endsection

@push('scripts')
    <script>
        window.onload = (function() {
            var percentage = {};
            <?php foreach ($reviewRepository->getPercentageRating($supplier) as $key => $count) { ?>

            percentage = <?php echo "'$count';"; ?>
            id = <?php echo "'$key';"; ?>
            idNumber = id + 'star';

            document.getElementById(id).style.width = percentage + "%";
            document.getElementById(id).style.height = 4 + "px";
            document.getElementById(idNumber).innerHTML = id;

            <?php } ?>
        })();
    </script>
@endpush
