<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'date_created' => medium_date($this->created_at),
            'last_updated' => time_diff($this->updated_at),
            'trashed' => $this->deleted_at
        ];
    }
}
