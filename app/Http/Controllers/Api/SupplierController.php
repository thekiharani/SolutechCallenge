<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierListResource;
use App\Http\Resources\SupplierShowResource;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    /**
     * @var SupplierRepository
     */
    public $repository;

    /**
     * ProductController constructor.
     * @param SupplierRepository $repository
     */
    public function __construct(SupplierRepository $repository)
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
        $suppliers = SupplierListResource::collection($this->repository->list());
        return response()->json(['suppliers' => $suppliers], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SupplierRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SupplierRequest $request)
    {
        $supplier = $this->repository->store($request->only([
            'name', 'products',
        ]));

        if ($supplier instanceof Supplier) {
            return response()->json(['message' => 'created', 'supplier' => new SupplierShowResource($supplier)], Response::HTTP_CREATED);
        }
        return response()->json(['error' => $supplier], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Supplier $supplier)
    {
        return response()->json(['supplier' => new SupplierShowResource($supplier)], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SupplierRequest $request
     * @param \App\Models\Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier = $this->repository->update($supplier, $request->only([
            'name', 'products',
        ]));

        if ($supplier instanceof Supplier) {
            return response()->json(['message' => 'updated', 'supplier' => new SupplierShowResource($supplier)], Response::HTTP_OK);
        }
        return response()->json(['error' => $supplier], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(['message' => 'supplier trashed'], Response::HTTP_OK);
    }

    /**
     * @param $supplier_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($supplier_id) {
        Supplier::withTrashed()->find($supplier_id)->restore();
;        return response()->json(['message' => 'supplier restored'], Response::HTTP_OK);
    }
}
