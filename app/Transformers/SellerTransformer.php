<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Seller $seller
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'id'           => $seller->id,
            'name'         => $seller->name,
            'email'        => $seller->email,
            'isVerified'   => $seller->is_verified,
            'creationDate' => (string) $seller->created_at,
            'lastChanges'  => (string) $seller->updated_at,
            'deletionDate' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('sellers.show', $seller->id),
                ],
                [
                    'rel'  => 'user',
                    'href' => route('users.show', $seller->id),
                ],
                [
                    'rel'  => 'seller.buyer',
                    'href' => route('sellers.buyers.index', $seller->id),
                ],
                [
                    'rel'  => 'seller.categories',
                    'href' => route('sellers.categories.index', $seller->id),
                ],
                [
                    'rel'  => 'seller.products',
                    'href' => route('sellers.products.index', $seller->id),
                ],
                [
                    'rel'  => 'seller.transactions',
                    'href' => route('sellers.transactions.index', $seller->id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id'           => 'id',
            'name'         => 'name',
            'email'        => 'email',
            'isVerified'   => 'verified',
            'creationDate' => 'created_at',
            'lastChanges'  => 'updated_at',
            'deletionDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'         => 'id',
            'name'       => 'name',
            'email'      => 'email',
            'verified'   => 'isVerified',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChanges',
            'deleted_at' => 'deletionDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
