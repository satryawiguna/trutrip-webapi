<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class ProfileTransformer extends TransformerAbstract
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
            'nick_name' => $user->contact->nick_name,
            'full_name' => $user->contact->full_name,
            'post_code' => $user->contact->post_code,
            'country' => $user->contact->country,
            'state' => $user->contact->state,
            'city' => $user->contact->city,
            'address' => $user->contact->address,
            'mobile' => $user->contact->mobile,
            'photo' => $user->contact->photo
        ];
    }
}
