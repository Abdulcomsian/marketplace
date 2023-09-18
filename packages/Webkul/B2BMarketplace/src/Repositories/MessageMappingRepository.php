<?php

namespace Webkul\B2BMarketplace\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * Message Mapping Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MessageMappingRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  Illuminate\Container\Container   $app
     * @return void
     */

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\MessageMapping';
    }
}