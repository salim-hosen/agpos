<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Services\PurchaseService;

/**
 * @OA\Schema(
 *     schema="Purchase",
 *     type="object",
 *     required={"purchase_id", "supplier_id", "total_amount", "purchase_date", "created_at", "updated_at"},
 *     @OA\Property(property="purchase_id", type="integer", description="Purchase ID"),
 *     @OA\Property(property="supplier_id", type="integer", description="Supplier ID"),
 *     @OA\Property(property="total_amount", type="number", format="float", description="Total Amount"),
 *     @OA\Property(property="purchase_date", type="string", format="date", description="Purchase Date"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation Timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last Update Timestamp"),
 * )
 */

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * @OA\Get(
     *     path="/api/purchases",
     *     summary="Get a list of purchase orders",
     *     tags={"Purchases"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of purchase orders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Purchase")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $purchases = $this->purchaseService->getPurchaseOrders();
        return response()->json($purchases);
    }

    /**
     * @OA\Post(
     *     path="/api/purchases",
     *     summary="Create a new purchase order",
     *     tags={"Purchases"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"supplier_id", "purchase_date", "items"},
     *             @OA\Property(property="supplier_id", type="integer"),
     *             @OA\Property(property="purchase_date", type="string", format="date"),
     *             @OA\Property(property="items", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="unit_price", type="number", format="float")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The purchase order has been created successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Purchase")
     *     )
     * )
     */
    public function store(PurchaseRequest $request)
    {
        $purchase = $this->purchaseService->createPurchase($request->validated());
        return response()->json($purchase, 201);
    }
}
