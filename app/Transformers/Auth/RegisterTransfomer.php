<?php

namespace App\Transformers\Auth;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class RegisterTransfomer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'username' => $user->username,
            'email' => $user->email,
            'contact' => [
                'full_name' => $user->contact->full_name,
                'nick_name' => $user->contact->nick_name
            ]
        ];
    }
}
