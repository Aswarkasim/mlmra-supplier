<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class CategoryResource extends JsonResource
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
            'id'       => $this->id,
            'name'     => $this->name,
            'category_type' => $this->category_type,
            'status' =>  $this->status,
            'path' => $this->media ? $this->media->path : null,
            // 'image' => $this->media ? env('APP_IMAGE_URL') . $this->media->file_name : null,
            'image' => $this->media ? $this->media->file_name : null,
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
        ];
    }
}
