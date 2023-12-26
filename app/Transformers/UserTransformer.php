<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     */
    public function transform(User $user): array
    {
        return [
            'id'           => (int) $user->id,
            'name'         => (string) $user->name,
            'email'        => (string) $user->email,
            'isVerified'   => (bool) $user->is_verified,
            'isAdmin'      => (bool) $user->is_admin,
            'creationDate' => (string) $user->created_at,
            'lastChanges'  => (string) $user->updated_at,
            'deletionDate' => isset($user->deleted_at) ? (string) $user->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('users.show', $user->id),
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
            'isAdmin'      => 'is_admin',
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
            'name'        => 'name',
            'email'       => 'email',
            'is_verified' => 'isVerified',
            'is_admin'    => 'isAdmin',
            'created_at'  => 'creationDate',
            'updated_at'  => 'lastChanges',
            'deleted_at'  => 'deletionDate',
        ];

        return $attributes[$index] ?? null;
    }
}
