<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Supplier Category Reposotory
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AllowedSupplierCategoryRepository extends Repository
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\AllowedSupplierCategory';
    }
}