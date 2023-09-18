<accordian :title="'{{ __('b2b_marketplace::app.admin.products.flag.flag-title') }}'" :active="'true'">
    <div slot="body">
        {!! app('Webkul\B2BMarketplace\DataGrids\Admin\ProductFlagDataGrid')->render() !!}
    </div>
</accordian>