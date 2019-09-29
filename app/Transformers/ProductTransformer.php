<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id'           => (int)$product->id,
            'title'        => (string)$product->title,
            'details'      => (string)$product->description,
            'stock'        => (int)$product->quantity,
            'situation'    => (int)$product->status,
            'picture'      => url("img/{$product->image}"),
            'seller'       => (int)$product->seller_id,
            'creationDate' => (string)$product->created_at,
            'lastChanges'  => (string)$product->updated_at,
            'deletionDate' => isset($product->deleted_at) ? (string)$product->deleted_at : null
        ];
    }
}
