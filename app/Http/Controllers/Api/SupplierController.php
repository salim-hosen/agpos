<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;
use App\Services\SupplierService;
use App\Models\Supplier;

/**
 * @OA\Tag(
 *     name="Suppliers",
 *     description="Operations related to suppliers"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Supplier",
 *     type="object",
 *     required={"name", "contact_info", "address"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="contact_info", type="string"),
 *     @OA\Property(property="address", type="string")
 * )
 */
class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * @OA\Get(
     *     path="/api/suppliers",
     *     summary="Fetch a paginated list of suppliers with filters by name",
     *     tags={"Suppliers"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Filter by supplier name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of suppliers",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Supplier")
     *             ),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Get filters from the request
        $filters = $request->only(['name']);

        // Get suppliers from the service
        $suppliers = $this->supplierService->getAllSuppliers($filters);

        return response()->json($suppliers);
    }

    /**
     * @OA\Post(
     *     path="/api/suppliers",
     *     summary="Create a new supplier",
     *     tags={"Suppliers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Supplier created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     )
     * )
     */
    public function store(SupplierRequest $request)
    {
        $supplier = $this->supplierService->createSupplier($request->validated());
        return response()->json($supplier, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/suppliers/{id}",
     *     summary="Get details of a specific Supplier",
     *     tags={"Suppliers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the supplier",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supplier details",
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     )
     * )
     */
    public function show(Supplier $supplier)
    {
        return response()->json($supplier, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/suppliers/{supplier_id}",
     *     summary="Update an existing supplier",
     *     tags={"Suppliers"},
     *     @OA\Parameter(
     *         name="supplier_id",
     *         in="path",
     *         required=true,
     *         description="ID of the supplier to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supplier updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     )
     * )
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier = $this->supplierService->updateSupplier($supplier, $request->validated());
        return response()->json($supplier, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/suppliers/{supplier_id}",
     *     summary="Delete a supplier",
     *     tags={"Suppliers"},
     *     @OA\Parameter(
     *         name="supplier_id",
     *         in="path",
     *         required=true,
     *         description="ID of the supplier to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Supplier deleted successfully"
     *     )
     * )
     */
    public function destroy(Supplier $supplier)
    {
        return response()->json($this->supplierService->deleteSupplier($supplier), 200);
    }
}
