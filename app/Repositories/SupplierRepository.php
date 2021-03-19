<?php


namespace App\Repositories;


use App\Models\Supplier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SupplierRepository
{
    /**
     * @return mixed
     */
    public function list() {
        return Supplier::paginate(20);
    }

    /**
     * @param array $attributes
     * @return Supplier|string
     */
    public function store(array $attributes)
    {
        DB::beginTransaction();
        try {
            $supplier = Supplier::create([
                'name' => $attributes['name'],
            ]);

            // Associate supplier with products if any
            if (Arr::exists($attributes, 'products') && sizeof($attributes['products'])) {
                $supplier->products()->attach($attributes['products']);
            }
            DB::commit();
            return $supplier;
        } catch (\Throwable $e) {
            DB::rollBack();
            return 'error';
        }
    }

    /**
     * @param Supplier $supplier
     * @param array $attributes
     * @return Supplier|string
     */
    public function update(Supplier $supplier, array $attributes)
    {
        DB::beginTransaction();
        try {
            $supplier->upadte([
                'name' => $attributes['name'],
            ]);

            // Update supplier products
            if (Arr::exists($attributes, 'products')) {
                $supplierProducts = $supplier->products()->pluck('id')->toArray();
                $productsAttrs = $attributes['products'];
                $detachproducts = array_diff($supplierProducts, $productsAttrs);
                $attachproducts = array_diff($productsAttrs, $supplierProducts);
                $supplier->products()->detach($detachproducts);
                $supplier->products()->attach($attachproducts);
            }
            DB::commit();
            return $supplier;
        } catch (\Throwable $e) {
            DB::rollBack();
            return 'error';
        }
    }
}
