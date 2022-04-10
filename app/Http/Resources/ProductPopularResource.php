<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPopularResource extends JsonResource
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
                'product' => $this->product ? $this->product : null,
                'supplier' => $this->user ? $this->user : null,
                'category' => $this->product->category ? $this->product->category->name : null,
                'media' => Media::whereCode($this->product->media_code)->get(),
            ],
        ];
    }
}
