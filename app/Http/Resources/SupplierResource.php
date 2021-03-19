<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'products' => $this->when(
                $this->products()->count(),
                ProductResource::collection($this->products)->except(['suppliers'])
            ),
            'date_created' => $this->created_at->format('jS F, Y | g:i A'),
            'last_updated' => $this->updted_at->diffForHumans(),
        ];
    }
}
