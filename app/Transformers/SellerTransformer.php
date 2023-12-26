<?php

namespace App\Transformers;

use App\Models\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     */
    public function transform(Seller $seller): array
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

    public static function originalAttribute(string $index): string|null
    {
        $attributes = [
            'id'           => 'id',
            'name'         => 'name',
            'email'        => 'email',
            'isVerified'   => 'is_verified',
            'creationDate' => 'created_at',
            'lastChanges'  => 'updated_at',
            'deletionDate' => 'deleted_at',
        ];

        return $attributes[$index] ?? null;
    }

    public static function transformedAttribute(string $index): string|null
    {
        $attributes = [
            'id'         => 'id',
            'name'       => 'name',
            'email'      => 'email',
            'is_verified'   => 'isVerified',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChanges',
            'deleted_at' => 'deletionDate',
        ];

        return $attributes[$index] ?? null;
    }
}
