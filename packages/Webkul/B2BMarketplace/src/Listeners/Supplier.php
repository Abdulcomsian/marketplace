<?php

namespace Webkul\B2BMarketplace\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\User\Repositories\AdminRepository;
use Webkul\B2BMarketplace\Mail\SupplierWelcomeNotification;
use Webkul\B2BMarketplace\Mail\SupplierApprovalNotification;
use Webkul\B2BMarketplace\Mail\NewSupplierNotification;
use Webkul\B2BMarketplace\Mail\SupplierVerificationNotification;

/**
 * Supplier event handler
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Supplier
{
    /**
     * Supplier Repository object
     *
     * @var Supplier
    */
    protected $supplier;

    /**
     * AdminRepository object
     *
     * @var Supplier
    */
    protected $admin;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplier
     * @param  Webkul\User\Repositories\AdminRepository  $admin
     * @return void
     */
    public function __construct(SupplierRepository $supplier, AdminRepository $admin)
    {
        $this->supplier = $supplier;

        $this->admin = $admin;
    }

    /**
     * Register seller if customer requested
     *
     * @param mixed $customer
     */
    public function registerSupplier($supplier)
    {
        $admin = $this->admin->findOneWhere(['role_id' => 1]);

        if ($supplier) {
            $newSupplier = $this->supplier->findOneByField([
                    'url' => $supplier->url,
                    'id' => $supplier->id
            ]);

            try {
                if ($newSupplier->is_approved) {
                    Mail::send(new SupplierApprovalNotification($newSupplier));
                } else {
                    Mail::send(new SupplierWelcomeNotification($newSupplier));

                    Mail::send(new NewSupplierNotification($newSupplier, $admin));
                }

                if (! $newSupplier->is_verified) {
                    Mail::send(new SupplierVerificationNotification($newSupplier));
                }
            } catch (\Exception $e) {}

        }
    }

    /**
     * Delete inventory of Supplier after delete
     *
     * @param mixed $id
     */
    public function afterSupplierDelete($id) {
        $this->supplier->deleteInventory($id);
    }
}
