<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class RoleRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Role';
    }
}