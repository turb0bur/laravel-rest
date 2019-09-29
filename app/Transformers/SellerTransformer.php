<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Seller $seller
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'id'           => (int)$seller->id,
            'name'         => (string)$seller->name,
            'email'        => (string)$seller->email,
            'isVerified'   => (int)$seller->verified,
            'creationDate' => (string)$seller->created_at,
            'lastChanges'  => (string)$seller->updated_at,
            'deletionDate' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null
        ];
    }

    public static function originalAtrribute($index)
    {
        $attributes = [
            'id'           => 'id',
            'name'         => 'name',
            'email'        => 'email',
            'isVerified'   => 'verified',
            'creationDate' => 'created_at',
            'lastChanges'  => 'updated_at',
            'deletionDate' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
