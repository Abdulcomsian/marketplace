<?php

namespace Webkul\B2BMarketplace\Repositories;

use DB;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Product\Repositories\ProductInventoryRepository;

class SupplierRepository extends Repository
{
    /**
     * ProductInventoryRepository object
     *
     * @var object
     */
    protected $productInventoryRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Product\Repositories\ProductInventoryRepository $productInventoryRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */
    public function __construct(
        ProductInventoryRepository $productInventoryRepository,
        App $app
    )
    {
        $this->productInventoryRepository = $productInventoryRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Supplier';
    }

     /**
     * Checks if supplier is approved or not
     *
     * @param $supplierId
     * @return boolean
     */
    public function isSupplierApproved($supplierId)
    {
        $isSupplierApproved = $this->getModel()->where('id', $supplierId)
            ->where('is_approved', 1)
            ->limit(1)
            ->select(\DB::raw(1))
            ->exists();

        return $isSupplierApproved ? true : false;
    }

    /**
     * Retrive supplier from url
     *
     * @param string $url
     * @return mixed
     */
    public function findByUrlOrFail($url, $columns = null)
    {
        if ($supplier = $this->findOneByField('url', $url)) {
            return $supplier;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $url
        );
    }

    /**
     * Returns top 4 popular sellers
     *
     * @return Collection
     */
    public function getPopularSellers()
    {
        $result = $this->getModel()
            ->leftJoin('b2b_marketplace_orders', 'b2b_marketplace_suppliers.id', 'b2b_marketplace_orders.supplier_id')
            ->leftJoin('b2b_marketplace_order_items', 'b2b_marketplace_orders.id', 'b2b_marketplace_order_items.b2b_marketplace_order_id')
            ->leftJoin('order_items', 'b2b_marketplace_order_items.order_item_id', 'order_items.id')
            ->addSelect('b2b_marketplace_suppliers.*')
            ->addSelect(DB::raw('SUM(qty_ordered) as total_qty_ordered'))
            ->groupBy('b2b_marketplace_suppliers.id')
            ->where('b2b_marketplace_suppliers.company_name', '<>', NULL)
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(4)
            ->get();

        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        Event::dispatch('b2b_marketplace.supplier.delete.before', $id);

        parent::delete($id);

        Event::dispatch('b2b_marketplace.supplier.delete.after', $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteInventory($id)
    {
        $inventories = $this->productInventoryRepository->findWhere([
            'vendor_id' => $id
        ]);

        if (count($inventories)) {
            foreach ($inventories as $inventory) {
                if (isset ($inventory)) {
                    $this->productInventoryRepository->delete($inventory->id);
                }
            }
        }
    }
}