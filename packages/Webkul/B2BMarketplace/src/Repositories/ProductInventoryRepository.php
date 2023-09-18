<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Product Inventory Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductInventoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\ProductInventory';
    }

    /**
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function saveInventories(array $data, $product)
    {
        if ($product->type == 'configurable')
            return;

        if (isset($data['inventories'])) {
            foreach ($data['inventories'] as $inventorySourceId => $qty) {
                if (is_null($qty)) {
                    $qty = 0;
                }

                $productInventory = $this->findOneWhere([
                    'product_id' => $product->id,
                    'inventory_source_id' => $inventorySourceId,
                    'supplier' => Null,
                    'vendor_id' => $data['vendor_id']
                ]);

                if ($productInventory) {

                    $productInventory->qty = $qty;

                    $productInventory->save();
                } else {

                    $this->create([
                        'qty' => $qty,
                        'product_id' => $product->id,
                        'inventory_source_id' => $inventorySourceId,
                        'supplier' => Null,
                        'vendor_id' => $data['vendor_id']
                    ]);
                }
            }
        }
    }
}