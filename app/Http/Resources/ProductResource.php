<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'sub_data' => parent::toArray($request),
                'supplier' => $this->user ? $this->user->username : null,
                'category' => $this->category ? $this->category->name : null,
                'sub_category' => $this->subcategory ? $this->subcategory->name : null,
                'media' => Media::whereCode($this->media_code)->get(),
            ],
        ];
    }
}
