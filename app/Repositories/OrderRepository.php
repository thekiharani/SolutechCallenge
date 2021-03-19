<?php


namespace App\Repositories;


use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    /**
     * @return mixed
     */
    public function list() {
        return Order::paginate(20);
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
                'order_number' => $attributes['order_number'],
            ]);

            // Associate order with products if any
            if (Arr::exists($attributes, 'products') && sizeof($attributes['products'])) {
                $order->products()->attach($attributes['products']);
            }
            DB::commit();
            return $order;
        } catch (\Throwable $e) {
            DB::rollBack();
            return 'error';
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
                $orderProducts = $order->products()->pluck('id')->toArray();
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
            return 'error';
        }
    }
}
