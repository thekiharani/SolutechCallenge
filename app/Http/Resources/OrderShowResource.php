<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderShowResource extends JsonResource
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
            'products' => $this->when($this->products()->exists(), ProductListResource::collection($this->products)),
            'date_created' => $this->created_at->format('jS F, Y | g:i A'),
            'last_updated' => $this->updated_at->diffForHumans(),
        ];
    }
}
