<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResellerResource extends JsonResource
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
                'image' => $this->media ? env('APP_IMAGE_URL').$this->media->file_name : null
            ]
        ];
    }
}
