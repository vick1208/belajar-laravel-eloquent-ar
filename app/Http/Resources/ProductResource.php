<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @mixin Product
 */

class ProductResource extends JsonResource
{


    public static $wrap = "value";
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "category" => new CategorySimplifiedResource($this->category),
            "price" => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ) ;
    }
}