<?php

namespace App\Transformers;

use App\Models\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     */
    public function transform(Buyer $buyer): array
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
