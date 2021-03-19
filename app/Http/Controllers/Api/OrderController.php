<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    public $repository;

    /**
     * ProductController constructor.
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
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
        $orders = OrderResource::collection($this->repository->list());
        return response()->json(['orders' => $orders], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderRequest $request)
    {
        $order = $this->repository->store($request->only([
            'order_id', 'products',
        ]));

        if ($order == 'error') {
            return response()->json(['error' => 'error in creating order'], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['message' => 'created', 'order' => $order], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        return response()->json(['order' => new OrderResource($order)], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderRequest $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order = $this->repository->update($order, $request->only([
            'order_id', 'products',
        ]));

        if ($order == 'error') {
            return response()->json(['error' => 'error in updating order'], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['message' => 'updated', 'order' => $order], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'order trashed'], Response::HTTP_OK);
    }
}
