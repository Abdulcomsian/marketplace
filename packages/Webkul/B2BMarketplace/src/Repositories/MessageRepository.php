<?php

namespace Webkul\B2BMarketplace\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\B2BMarketplace\Repositories\MessageMappingRepository;

/**
 * Message Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MessageRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var object
     */
    protected $attribute;

    /**
     * ProductRepository object
     *
     * @var object
     */
    protected $productRepository;

    /**
     * ProductInventoryRepository object
     *
     * @var object
     */
    protected $productInventoryRepository;

    /**
     * Supplier Repository object
     *
     * @var object
     */
    protected $SupplierRepository;

    /**
     * Message Mapping Repository object
     *
     * @var object
     */
    protected $messageMapping;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\MessageMappingRepository $messageMapping
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository  $supplierRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */

    public function __construct(
        MessageMappingRepository $messageMapping,
        SupplierRepository $supplierRepository,
        App $app
    )
    {
        $this->messageMapping = $messageMapping;

        $this->supplierRepository = $supplierRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Message';
    }

    /**
     * Search Messages
     *
     * @param $term
     * @return Collection
     */
    public function searchCustomerMsg($term, $supplierId)
    {
        $results = $this->messageMapping->scopeQuery(function($query) use($term, $supplierId) {

            return $query->distinct()
                ->addSelect('b2b_marketplace_message_mappings.*')
                ->leftJoin('customers' , 'b2b_marketplace_message_mappings.customer_id', '=', 'customers.id')
                ->where('b2b_marketplace_message_mappings.supplier_id', '=', $supplierId)
                ->where('customers.first_name', 'like', '%' . $term . '%')
                ->select('customers.first_name', 'b2b_marketplace_message_mappings.customer_id', 'b2b_marketplace_message_mappings.supplier_id');

        })->paginate(16);

        return $results;
    }

    /**
     * Search Messages
     *
     * @param $term
     * @return Collection
     */
    public function searchSupplierMsg($term, $customerId)
    {
        $results = $this->messageMapping->scopeQuery(function($query) use($term, $customerId) {

        return $query->distinct()
            ->addSelect('b2b_marketplace_message_mappings.*')
            ->leftJoin('customers' , 'b2b_marketplace_message_mappings.customer_id', '=', 'customers.id')
            ->leftJoin('b2b_marketplace_suppliers' , 'b2b_marketplace_message_mappings.supplier_id', '=', 'b2b_marketplace_suppliers.id')
            ->where('b2b_marketplace_message_mappings.customer_id', '=', $customerId)
            ->where('b2b_marketplace_suppliers.first_name', 'like', '%' . $term . '%')
            ->select('b2b_marketplace_suppliers.first_name', 'b2b_marketplace_message_mappings.customer_id', 'b2b_marketplace_message_mappings.supplier_id');
        })->paginate(16);

        return $results;
    }
}