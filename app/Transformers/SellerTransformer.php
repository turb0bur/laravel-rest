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
}
