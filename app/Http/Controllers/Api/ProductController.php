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

        if ($product == 'error') {
            return response()->json(['error' => 'error in creating product'], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['message' => 'created', 'product' => $product], Response::HTTP_CREATED);
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

        if ($product == 'error') {
            return response()->json(['error' => 'error in updating product'], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['message' => 'updated', 'product' => $product], Response::HTTP_OK);
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
}
