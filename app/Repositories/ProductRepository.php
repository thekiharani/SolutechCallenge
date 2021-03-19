<?php


namespace App\Repositories;


use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    /**
     * @return mixed
     */
    public function list() {
        return Product::paginate(20);
    }

    /**
     * @param array $attributes
     * @return Product|string
     */
    public function store(array $attributes)
    {
        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $attributes['name'],
                'quantity' => $attributes['quantity'],
                'description' => $attributes['description']
            ]);
            DB::commit();
            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();
            return 'error :' . $e->getMessage();
        }
    }

    /**
     * @param Product $product
     * @param array $attributes
     * @return Product|string
     */
    public function update(Product $product, array $attributes)
    {
        DB::beginTransaction();
        try {
            $product->update([
                'name' => $attributes['name'],
                'quantity' => $attributes['quantity'],
                'description' => $attributes['description']
            ]);
            DB::commit();
            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();
            return 'error :' . $e->getMessage();
        }
    }
}
