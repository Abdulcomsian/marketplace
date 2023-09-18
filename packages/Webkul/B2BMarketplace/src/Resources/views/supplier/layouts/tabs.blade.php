<div class="tabs">
@php
    $allDataGrid = [
        'new' => app('Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus\NewDataGrid'),
        'pending' => app('Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus\PendingDataGrid'),
        'answered' => app('Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus\AnsweredDataGrid'),
        'confirmed' => app('Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus\ConfirmedDataGrid'),
        'rejected' => app('Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus\RejectedDataGrid')
    ];
@endphp

    @if (request()->route()->getName() != 'admin.configuration.index')

        <?php $keys = explode('.', $menu->currentKey);  ?>


        @if ($items = \Illuminate\Support\Arr::get($menu->items, implode('.children.', array_slice($keys, 0, 2)) . '.children'))

            <ul>

                @foreach (\Illuminate\Support\Arr::get($menu->items, implode('.children.', array_slice($keys, 0, 2)) . '.children') as $item)

                    <li class="{{ $menu->getActive($item) }}">
                        <a href="{{ $item['url'] }}">
                            {{ trans($item['name']) }}


                            @if($item['name'] == 'New' && $allDataGrid['new']->export()->count() > 0)
                                <span class="message-unseen-count">

                                    {{ $allDataGrid['new']->export()->count() }}
                                </span>
                            @elseif ($item['name'] == 'Pending' && $allDataGrid['pending']->export()->count() > 0)
                                <span class="message-unseen-count">

                                    {{ $allDataGrid['pending']->export()->count() }}
                                </span>

                            @elseif ($item['name'] == 'Answered' &&$allDataGrid['answered']->export()->count() > 0)
                                <span class="message-unseen-count">

                                    {{ $allDataGrid['answered']->export()->count() }}
                                </span>
                            @elseif ($item['name'] == 'Confirmed' && $allDataGrid['confirmed']->export()->count() > 0)
                                <span class="message-unseen-count">

                                    {{ $allDataGrid['confirmed']->export()->count() }}
                                </span>
                            @elseif ($item['name'] == 'Rejected' &&$allDataGrid['rejected']->export()->count() > 0)
                                <span class="message-unseen-count">

                                    {{ $allDataGrid['rejected']->export()->count() }}
                                </span>
                            @endif

                        </a>
                    </li>

                @endforeach

            </ul>

        @endif

    @else

        @if ($items = array_get($config->items, request()->route('slug') . '.children'))

            <ul>

                @foreach ($items as $key => $item)

                    <li class="{{ $key == request()->route('slug2') ? 'active' : '' }}">
                        <a href="{{ route('admin.configuration.index', (request()->route('slug') . '/' . $key)) }}">
                            {{ trans($item['name']) }}
                        </a>
                    </li>

                @endforeach

            </ul>

        @endif

    @endif
</div>