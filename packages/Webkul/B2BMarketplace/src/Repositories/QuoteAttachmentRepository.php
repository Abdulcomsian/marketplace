<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;

/**
 * Quote Attachment Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class QuoteAttachmentRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\QuoteAttachment';
    }

    /**
     * Upload Qupte Image's
     *
     * @param array $data
     * @param mixed $quote
     * @return mixed
     */
    public function uploadFiles($data, $quote)
    {
        $previousFileIds = $quote->attachments()->pluck('id');

        if (isset($data['files'])) {

            $data['files'] = [
                'file_1' => $data['files'],
            ];

            foreach ($data['files'] as $fileId => $image) {
                $file = 'files';
                $dir = 'quoteAttachment/' . $quote->id;

                if (str_contains($fileId, 'file_')) {

                    if (request()->hasFile($file)) {
                        $this->create([
                            'path' => request()->file($file)->store($dir),
                            'customer_quote_id' => $quote->id
                        ]);
                    }
                } else {
                    if (is_numeric($index = $previousFileIds->search($fileId))) {
                        $previousFileIds->forget($index);
                    }

                    if (request()->hasFile($file)) {
                        if ($fileModel = $this->find($fileId)) {
                            Storage::delete($fileModel->path);
                        }

                        $this->update([
                                'path' => request()->file($file)->store($dir)
                            ], $fileId);
                    }
                }
            }
        }

        foreach ($previousFileIds as $fileId) {
            if ($fileModel = $this->find($fileId)) {
                Storage::delete($fileModel->path);

                $this->delete($fileId);
            }
        }
    }
}