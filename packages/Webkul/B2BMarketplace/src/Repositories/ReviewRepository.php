<?php

namespace Webkul\B2BMarketplace\Repositories;

use DB;
use Webkul\Core\Eloquent\Repository;

/**
 * SUpplier Review Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Review';
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function getRecentReviews($supplierId = null)
    {
        return $this->scopeQuery(function($query) use($supplierId) {
            return $query->distinct()->where('supplier_id', $supplierId)->where('status', 'approved')->orderBy('id', 'desc');
        })->paginate(5);
    }

    /**
     * Returns the supplier's avg rating
     *
     * @param Supplier $supplier
     * @return float
     */
    public function getReviews($supplier)
    {
        return $supplier->reviews()->where('status', 'approved');
    }

    /**
     * Returns the supplier's avg rating
     *
     * @param Supplier $supplier
     * @return float
     */
    public function getAverageRating($supplier)
    {
        return number_format(round($supplier->reviews()->where('status', 'approved')->average('rating'), 2), 1);
    }

    /**
     * Returns the total review of the supplier
     *
    * @param Supplier $supplier
     * @return integer
     */
    public function getTotalReviews($supplier)
    {
        return $supplier->reviews()->where('status', 'approved')->count();
    }

     /**
     * Returns the total rating of the supplier
     *
     * @param Supplier $supplier
     * @return integer
     */
    public function getTotalRating($supplier)
    {
        return $supplier->reviews()->where('status','approved')->sum('rating');
    }

     /**
     * Returns the Percentage rating of the supplier
     *
    * @param Supplier $supplier
     * @return integer
     */
    public function getPercentageRating($supplier)
    {
        $reviews = $supplier->reviews()->where('status','approved')
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating','desc')
            ->get();

        for ($i = 5; $i >= 1; $i--) {
            if (! $reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = round(($review->total / $this->getTotalReviews($supplier)) * 100);
                        break;
                    } else {
                        $percentage[$i] = 0;
                    }
                }
            } else {
                $percentage[$i] = 0;
            }
        }

        return $percentage;
    }
}