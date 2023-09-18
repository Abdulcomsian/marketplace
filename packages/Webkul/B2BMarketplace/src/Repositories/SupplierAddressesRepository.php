<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class SupplierAddressesRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\SupplierAddresses';
    }

    /**
     * @param array $data
     * @param mixed $supplier
     * @return void
     */
    public function uploadImages($data, $supplier, $type = "logo")
    {
        if (isset($data[$type])) {
            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'Supplier/' . $supplier->id;

                if (request()->hasFile($file)) {
                    if ($supplier->{$type}) {

                        Storage::delete($supplier->{$type});
                    }

                    $supplier->{$type} = request()->file($file)->store($dir);
                    $data = $supplier->save();
                }
            }
        } else {
            if ($supplier->{$type}) {
                Storage::delete($supplier->{$type});
            }

            $supplier->{$type} = null;
            $data = $supplier->save();
        }
    }
}