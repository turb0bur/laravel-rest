<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Buyer $buyer
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'id'           => $buyer->id,
            'name'         => $buyer->name,
            'email'        => $buyer->email,
            'isVerified'   => $buyer->is_verified,
            'creationDate' => (string) $buyer->created_at,
            'lastChanges'  => (string) $buyer->updated_at,
            'deletionDate' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('buyers.show', $buyer->id),
                ],
                [
                    'rel'  => 'user',
                    'href' => route('users.show', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.sellers',
                    'href' => route('buyers.sellers.index', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.categories',
                    'href' => route('buyers.categories.index', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.products',
                    'href' => route('buyers.products.index', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.transactions',
                    'href' => route('buyers.transactions.index', $buyer->id),
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
