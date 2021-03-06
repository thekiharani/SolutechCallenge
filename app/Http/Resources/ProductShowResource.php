<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
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
            'listedSuppliers' => $this->suppliers()->count(),
            'placedOrders' => $this->orders()->count(),
            'orders' => $this->when($this->orders()->exists(), OrderListResource::collection($this->orders)),
            'suppliers' => $this->when($this->suppliers()->exists(), SupplierListResource::collection($this->suppliers)),
            'date_created' => medium_date($this->created_at),
            'last_updated' => time_diff($this->updated_at),
        ];
    }
}
