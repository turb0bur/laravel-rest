<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Buyer $buyer
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'id'           => (int)$buyer->id,
            'name'         => (string)$buyer->name,
            'email'        => (string)$buyer->email,
            'isVerified'   => (int)$buyer->verified,
            'creationDate' => (string)$buyer->created_at,
            'lastChanges'  => (string)$buyer->updated_at,
            'deletionDate' => isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null
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
