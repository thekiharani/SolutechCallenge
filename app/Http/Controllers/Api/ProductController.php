<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    public $repository;

    /**
     * ProductController constructor.
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = ProductResource::collection($this->repository->list());
        return response()->json(['products' => $products], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $product = $this->repository->store($request->only([
            'name', 'quantity', 'description'
        ]));

        if ($product instanceof Product) {
            return response()->json(['message' => 'created', 'product' => $product], Response::HTTP_CREATED);
        }
        return response()->json(['error' => $product], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return response()->json(['product' => new ProductResource($product)], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->repository->update($product, $request->only([
            'name', 'quantity', 'description'
        ]));

        if ($product instanceof Product) {
            return response()->json(['message' => 'updated', 'product' => $product], Response::HTTP_OK);
        }
        return response()->json(['error' => $product], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'product trashed'], Response::HTTP_OK);
    }

    /**
     * @param $product_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($product_id) {
        Product::withTrashed()->find($product_id)->restore();
;        return response()->json(['message' => 'product restored'], Response::HTTP_OK);
    }
}
