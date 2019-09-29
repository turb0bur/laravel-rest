<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Transaction $transaction
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id'           => (int)$transaction->id,
            'quantity'     => (string)$transaction->quantity,
            'buyer'        => (string)$transaction->buyer_id,
            'product'      => (int)$transaction->product_id,
            'creationDate' => (string)$transaction->created_at,
            'lastChanges'  => (string)$transaction->updated_at,
            'deletionDate' => isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('transactions.show', $transaction->id)
                ],
                [
                    'rel'  => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id)
                ],
                [
                    'rel'  => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id)
                ],
                [
                    'rel'  => 'product',
                    'href' => route('products.show', $transaction->product_id)
                ],
                [
                    'rel'  => 'transaction.categories',
                    'href' => route('transactions.categories.index', $transaction->id)
                ],
            ]
        ];
    }

    public static function originalAtrribute($index)
    {
        $attributes = [
            'id'           => 'id',
            'quantity'     => 'quantity',
            'buyer'        => 'buyer_id',
            'product'      => 'product_id',
            'creationDate' => 'created_at',
            'lastChanges'  => 'updated_at',
            'deletionDate' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
