<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


class PurchaseService
{
    protected $supplierLedgerService;

    public function __construct(SupplierLedgerService $supplierLedgerService)
    {
        $this->supplierLedgerService = $supplierLedgerService;
    }

    public function getPurchaseOrders()
    {
        return Purchase::with('supplier')->get();
    }

    public function createPurchase(array $data)
    {
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Create the purchase order
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'total_amount' => 0, // Set the total amount later
                'purchase_date' => $data['purchase_date'],
            ]);

            $totalAmount = 0;

            // Create the purchase items and update stock
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $totalPrice = $item['quantity'] * $item['unit_price'];

                // Create purchase item
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                ]);

                // Update the product stock quantity
                $product->current_stock_quantity += $item['quantity'];
                $product->save();

                // Add to total amount
                $totalAmount += $totalPrice;
            }

            // Update the purchase total amount
            $purchase->total_amount = $totalAmount;
            $purchase->save();

            // Add to Ledger
            // $this->supplierLedgerService->addPurchaseLedgerEntry($purchase);

            // Commit the transaction
            DB::commit();

            return $purchase;

        } catch (\Exception $e) {
            // Rollback if any error occurs
            DB::rollBack();
            throw $e;
        }
    }
}
