<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Category $buyer
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id'           => (int)$category->id,
            'title'        => (string)$category->name,
            'details'      => (string)$category->description,
            'creationDate' => (string)$category->created_at,
            'lastChanges'  => (string)$category->updated_at,
            'deletionDate' => isset($category->deleted_at) ? (string)$category->deleted_at : null
        ];
    }

    public static function originalAtrribute($index)
    {
        $attributes = [
            'id'           => 'id',
            'title'        => 'name',
            'details'      => 'description',
            'creationDate' => 'created_at',
            'lastChanges'  => 'updated_at',
            'deletionDate' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
