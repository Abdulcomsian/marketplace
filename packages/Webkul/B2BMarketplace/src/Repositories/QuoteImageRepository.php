<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;

/**
 * Quote Image Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class QuoteImageRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\QuoteImage';
    }

    /**
     * Upload Qupte Image's
     *
     * @param array $data
     * @param mixed $quote
     * @return mixed
     */
    public function uploadImages($data, $quote)
    {
        $previousImageIds = $quote->images()->pluck('id');

        if (isset($data['images'])) {
            foreach ($data['images'] as $imageId => $image) {
                $file = 'images.' . $imageId;
                $dir = 'quote/' . $quote->id;

                if (str_contains($imageId, 'image_')) {
                    if (request()->hasFile($file)) {
                        $this->create([
                                'path' => request()->file($file)->store($dir),
                                'customer_quote_id' => $quote->id
                            ]);
                    }
                } else {
                    if (is_numeric($index = $previousImageIds->search($imageId))) {
                        $previousImageIds->forget($index);
                    }

                    if (request()->hasFile($file)) {
                        if ($imageModel = $this->find($imageId)) {
                            Storage::delete($imageModel->path);
                        }

                        $this->update([
                            'path' => request()->file($file)->store($dir)
                        ], $imageId);
                    }
                }
            }
        }

        foreach ($previousImageIds as $imageId) {

            if ($imageModel = $this->find($imageId)) {

                Storage::delete($imageModel->path);

                $this->delete($imageId);
            }
        }
    }
}