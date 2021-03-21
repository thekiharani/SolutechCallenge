<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    /**
     * ProductController constructor.
     */
    public function __invoke()
    {
        return response()->json([
            'productCount' => Product::count(),
            'orderCount' => Order::count(),
            'supplierCount' => Supplier::count()
        ], Response::HTTP_OK);
    }

}
