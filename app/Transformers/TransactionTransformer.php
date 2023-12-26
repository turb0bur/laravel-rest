<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     */
    public function transform(Transaction $transaction): array
    {
        return [
            'id'           => (int) $transaction->id,
            'quantity'     => (string) $transaction->quantity,
            'buyer'        => (string) $transaction->buyer_id,
            'product'      => (int) $transaction->product_id,
            'creationDate' => (string) $transaction->created_at,
            'lastChanges'  => (string) $transaction->updated_at,
            'deletionDate' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel'  => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id),
                ],
                [
                    'rel'  => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id),
                ],
                [
                    'rel'  => 'product',
                    'href' => route('products.show', $transaction->product_id),
                ],
                [
                    'rel'  => 'transaction.categories',
                    'href' => route('transactions.categories.index', $transaction->id),
                ],
            ],
        ];
    }

    public static function originalAttribute(string $index): string|null
    {
        $attributes = [
            'id'           => 'id',
            'quantity'     => 'quantity',
            'buyer'        => 'buyer_id',
            'product'      => 'product_id',
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
            'quantity'   => 'quantity',
            'buyer_id'   => 'buyer',
            'product_id' => 'product',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChanges',
            'deleted_at' => 'deletionDate',
        ];

        return $attributes[$index] ?? null;
    }
}
