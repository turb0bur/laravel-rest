<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     */
    public function transform(Category $category): array
    {
        return [
            'id'           => (int) $category->id,
            'title'        => (string) $category->name,
            'details'      => (string) $category->description,
            'creationDate' => (string) $category->created_at,
            'lastChanges'  => (string) $category->updated_at,
            'deletionDate' => isset($category->deleted_at) ? (string) $category->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('categories.show', $category->id),
                ],
                [
                    'rel'  => 'category.buyers',
                    'href' => route('categories.buyers.index', $category->id),
                ],
                [
                    'rel'  => 'category.sellers',
                    'href' => route('categories.sellers.index', $category->id),
                ],
                [
                    'rel'  => 'category.products',
                    'href' => route('categories.products.index', $category->id),
                ],
                [
                    'rel'  => 'category.transactions',
                    'href' => route('categories.transactions.index', $category->id),
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
            'created_at'  => 'creationDate',
            'updated_at'  => 'lastChanges',
            'deleted_at'  => 'deletionDate',
        ];

        return $attributes[$index] ?? null;
    }
}
