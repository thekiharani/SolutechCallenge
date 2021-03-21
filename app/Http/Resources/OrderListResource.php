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
            'date_created' => $this->created_at->format('jS F, Y | g:i A'),
            'last_updated' => $this->updated_at->diffForHumans(),
        ];
    }
}
