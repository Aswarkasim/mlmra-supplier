<?php

namespace App\Http\Resources;

use App\Models\Media;
use App\Models\Referal;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferalResource extends JsonResource
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
            'jumlah_referal' => count(Referal::whereReferalCode($this->referal_code)->get()),
            'data' => [
                'referal_code' => $this->referal_code,
                'reseller' => $this->reseller ? $this->reseller->username : null,
                'foto' => $this->reseller->media ? env('APP_IMAGE_URL').$this->reseller->media->file_name : null
            ],
        ];
    }
}
