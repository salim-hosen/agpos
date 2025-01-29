<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SupplierLedgerService;

/**
 * @OA\Tag(
 *     name="Supplier Ledger",
 *     description="Operations related to supplier ledger"
 * )
 */

/**
 * @OA\Schema(
 *     schema="SupplierLedger",
 *     type="object",
 *     required={"ledger_id", "supplier_id", "transaction_date", "debit", "credit", "balance", "created_at", "updated_at"},
 *     @OA\Property(property="ledger_id", type="integer"),
 *     @OA\Property(property="supplier_id", type="integer"),
 *     @OA\Property(property="transaction_date", type="string", format="date"),
 *     @OA\Property(property="debit", type="number", format="float"),
 *     @OA\Property(property="credit", type="number", format="float"),
 *     @OA\Property(property="balance", type="number", format="float"),
 *     @OA\Property(property="remarks", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class SupplierLedgerController extends Controller
{
    protected $supplierLedgerService;

    public function __construct(SupplierLedgerService $supplierLedgerService)
    {
        $this->supplierLedgerService = $supplierLedgerService;
    }

    /**
     * @OA\Post(
     *     path="/api/ledger/payment",
     *     summary="Add ledger entry for a payment",
     *     tags={"Supplier Ledger"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"payment_id"},
     *             @OA\Property(property="payment_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ledger entry added successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierLedger")
     *     )
     * )
     */
    public function addPaymentLedgerEntry(Request $request)
    {
        $payment = Payment::findOrFail($request->payment_id); // Assuming a Payment model exists
        $ledger = $this->supplierLedgerService->addPaymentLedgerEntry($payment);
        return response()->json($ledger, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/ledger/{supplier_id}",
     *     summary="Get supplier ledger",
     *     tags={"Supplier Ledger"},
     *     @OA\Parameter(
     *         name="supplier_id",
     *         in="path",
     *         required=true,
     *         description="ID of the supplier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supplier's transaction ledger",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SupplierLedger"))
     *     )
     * )
     */
    public function getLedger($supplierId)
    {
        $ledger = $this->supplierLedgerService->getSupplierLedger($supplierId);
        return response()->json($ledger, 200);
    }
}
