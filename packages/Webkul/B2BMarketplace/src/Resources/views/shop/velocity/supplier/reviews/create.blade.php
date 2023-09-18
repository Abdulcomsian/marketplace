@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.reviews.create-title', ['shop_title' => $supplier->company_name]) }} - {{ $supplier->name }}
@endsection

@section('content-wrapper')

    <section class="profile-container review">

        @include('b2b_marketplace::shop.supplier.profile.left-profile')

        <div class="profile-right-block review-layouter" style="margin-top: 3%;">

            {!! view_render_event('b2b_marketplace.shop.supplier.reviews.create.before') !!}

            <div class="review-form">

                <form method="POST" action="{{ route('b2b_marketplace.reviews.store', $supplier->url) }}" @submit.prevent="onSubmit">

                    @csrf

                    <div class="heading mt-10">
                        <span>{{ __('b2b_marketplace::app.shop.supplier.reviews.write-review') }}</span>
                    </div>

                    <div class="form-container">

                        <div class="rating control-group" :class="[errors.has('rating') ? 'has-error' : '']">
                            <label class="required">{{ __('b2b_marketplace::app.shop.supplier.reviews.rating') }}</label>

                            <div class="stars">
                                <span class="star star-5" for="star-5" onclick="calculateRating(id)" id="1"></span>
                                <span class="star star-4" for="star-4" onclick="calculateRating(id)" id="2"></span>
                                <span class="star star-3" for="star-3" onclick="calculateRating(id)" id="3"></span>
                                <span class="star star-2" for="star-2" onclick="calculateRating(id)" id="4"></span>
                                <span class="star star-1" for="star-1" onclick="calculateRating(id)" id="5"></span>
                            </div>

                            <input type="hidden" id="rating" name="rating" v-validate="'required'">

                            <div class="control-error" v-if="errors.has('rating')">@{{ errors.first('rating') }}</div>
                        </div>

                        <div class="control-group" :class="[errors.has('comment') ? 'has-error' : '']">
                            <label for="comment" class="required">{{ __('b2b_marketplace::app.shop.supplier.reviews.comment') }}</label>
                            <textarea type="text" class="control" name="comment" v-validate="'required'" value="{{ old('comment') }}"  data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.reviews.comment') }}&quot;">
                            </textarea>
                            <span class="control-error" v-if="errors.has('comment')">@{{ errors.first('comment') }}</span>
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('shop::app.reviews.submit') }}
                        </button>

                    </div>

                </form>

            </div>

            {!! view_render_event('b2b_marketplace.shop.supplier.reviews.create.after') !!}

        </div>

    </section>

@endsection


@push('scripts')

    <script>

        function calculateRating(id) {
            var a = document.getElementById(id);
            document.getElementById("rating").value = id;

            for (let i=1 ; i <= 5 ; i++) {
                document.getElementById(i).style.color = id >= i ? "#242424" : "#d4d4d4";
            }
        }

    </script>

@endpush