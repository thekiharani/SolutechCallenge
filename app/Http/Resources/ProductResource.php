<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'orders' => $this->when(
                $this->orders()->count(),
                OrderResource::collection($this->orders)->except([])
            ),
            'suppliers' => $this->when(
                $this->suppliers()->count(),
                SupplierResource::collection($this->suppliers)->except([])
            ),
            'date_created' => $this->created_at->format('jS F, Y | g:i A'),
            'last_updated' => $this->updted_at->diffForHumans(),
        ];
    }
}
