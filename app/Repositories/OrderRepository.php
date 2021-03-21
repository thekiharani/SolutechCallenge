<?php


namespace App\Repositories;


use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository
{
    /**
     * @return mixed
     */
    public function list() {
        return Order::withTrashed()->paginate(20);
    }

    /**
     * @param array $attributes
     * @return Order|string
     */
    public function store(array $attributes)
    {
        DB::beginTransaction();
        try {
            $order = Order ::create([
                'order_number' => 'NA'
            ]);
            // Auto update order number
            $order_number = 'ORD-' . Str::padLeft($order->id, 4, '0');
            $order->update([
                'order_number' => $order_number
            ]);

            // Associate order with products if any
            if (Arr::exists($attributes, 'products') && sizeof($attributes['products'])) {
                $order->products()->attach($attributes['products']);
            }
            DB::commit();
            return $order;
        } catch (\Throwable $e) {
            DB::rollBack();
            return 'error :' . $e->getMessage();
        }
    }

    /**
     * @param Order $order
     * @param array $attributes
     * @return Order|string
     */
    public function update(Order $order, array $attributes)
    {
        DB::beginTransaction();
        try {
            // Update order products
            if (Arr::exists($attributes, 'products')) {
                $orderProducts = $order->products()->pluck('products.id')->toArray();
                $productsAttrs = $attributes['products'];
                $detachproducts = array_diff($orderProducts, $productsAttrs);
                $attachproducts = array_diff($productsAttrs, $orderProducts);
                $order->products()->detach($detachproducts);
                $order->products()->attach($attachproducts);
            }
            DB::commit();
            return $order;
        } catch (\Throwable $e) {
            DB::rollBack();
            return 'error :' . $e->getMessage();
        }
    }
}
