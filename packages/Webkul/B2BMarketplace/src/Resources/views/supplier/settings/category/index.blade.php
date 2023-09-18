@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.supplier.settings.category-title') }}
@stop

@section('content')
<div class="content">
    <form method="POST" action="{{ route('b2b_marketplace.supplier.profile.category.store') }}" @submit.prevent="onSubmit">

        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.supplier.settings.category-title') }}</h1>
            </div>

            <div class="page-action">
                <button type="submit" class="btn btn-primary btn-lg">
                    {{ __('b2b_marketplace::app.supplier.settings.save-btn-category') }}
                </button>
            </div>
        </div>

        <div class="page-content">
            @csrf()

            <accordian :title="'{{ __('b2b_marketplace::app.supplier.settings.category') }}'" :active="true">
                <div slot="body">
                    
                    <div class="control-group">
                        <label for="">{{ __('Categories') }}</label>

                        <?php $count = 0;?>
                        @foreach ($categories as $category)

                            <span class="checkbox" style="margin: 10px 5px 5px 0;">

                            <input type="checkbox" id="{{ $category['key'] }}" name="categories[]" value="{{ $category['key']}}"
                            @if ($category['status'] == 1) checked @endif>

                            <label class="checkbox-view" for="{{ $category['name'] }}"></label>
                            {{ $category['name'] }}

                            </span>
                        @endforeach
                    </div>
                </div>
            </accordian>
        </div>
    </form>
</div>
@endsection