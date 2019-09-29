<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'           => (int)$user->id,
            'name'         => (string)$user->name,
            'email'        => (string)$user->email,
            'isVerified'   => (int)$user->verified,
            'isAdmin'      => ($user->admin === 'true'),
            'creationDate' => (string)$user->created_at,
            'lastChanges'  => (string)$user->updated_at,
            'deletionDate' => isset($user->deleted_at) ? (string)$user->deleted_at : null
        ];
    }
}
