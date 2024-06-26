<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     */
    public function transform(Product $product): array
    {
        return [
            'id'           => (int) $product->id,
            'title'        => (string) $product->name,
            'details'      => (string) $product->description,
            'stock'        => (int) $product->quantity,
            'situation'    => (int) $product->status,
            'picture'      => url("img/{$product->image}"),
            'seller'       => (int) $product->seller_id,
            'creationDate' => (string) $product->created_at,
            'lastChanges'  => (string) $product->updated_at,
            'deletionDate' => isset($product->deleted_at) ? (string) $product->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel'  => 'product.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel'  => 'sellers',
                    'href' => route('sellers.show', $product->seller_id),
                ],
                [
                    'rel'  => 'product.categories',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel'  => 'product.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
            ],
        ];
    }

    public static function originalAttribute(string $index): string|null
    {
        $attributes = [
            'id'           => 'id',
            'title'        => 'name',
            'details'      => 'description',
            'stock'        => 'quantity',
            'situation'    => 'status',
            'picture'      => 'image',
            'seller'       => 'seller_id',
            'creationDate' => 'created_at',
            'lastChanges'  => 'updated_at',
            'deletionDate' => 'deleted_at',
        ];

        return $attributes[$index] ?? null;
    }

    public static function transformedAttribute(string $index): string|null
    {
        $attributes = [
            'id'          => 'id',
            'name'        => 'title',
            'description' => 'details',
            'quantity'    => 'stock',
            'status'      => 'situation',
            'image'       => 'picture',
            'seller_id'   => 'seller',
            'created_at'  => 'creationDate',
            'updated_at'  => 'lastChanges',
            'deleted_at'  => 'deletionDate',
        ];

        return $attributes[$index] ?? null;
    }
}
