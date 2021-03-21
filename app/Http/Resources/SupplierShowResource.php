<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierShowResource extends JsonResource
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
            'supplierProducts' => $this->products()->count(),
            'products' => $this->when($this->products()->exists(), ProductListResource::collection($this->products)),
            'productIDs' => $this->products()->pluck('products.id'),
            'date_created' => medium_date($this->created_at),
            'last_updated' => time_diff($this->updated_at),
        ];
    }
}
