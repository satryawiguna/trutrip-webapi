<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
    public function transform(Product $product)
    {
        return [
            "id" => $product->id,
            "user" => [
                "contact" => [
                    "nick_name" => $product->user->contact->nick_name
                ]
            ],
            "category" => [
                "name" => $product->category->name
            ],
            "name" => $product->name,
            "kilometer" => $product->kilometer,
            "color" => $product->color,
            "machine" => $product->machine,
            "passenger" => $product->passenger,
            "transmission" => $product->transmission,
            "photo" => $product->photo,
            "note" => $product->note,
            "specification" => $product->specification,
            "equipment" => $product->equipment
        ];
    }
}
