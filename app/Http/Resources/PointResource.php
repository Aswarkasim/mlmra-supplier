<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
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
            'point_total' => auth('reseller-api')->user()->point,
            'data' => [
                'point' => $this->point,
                'transaction_id' => $this->reseller_transaction ? $this->reseller_transaction->name : null
            ],
        ];
    }
}
