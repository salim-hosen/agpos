<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\Purchase;
use App\Models\Payment; // Assuming a Payment model exists

class SupplierLedgerService
{
    public function addPurchaseLedgerEntry(Purchase $purchase)
    {
        // Calculate the credit entry based on the total amount of the purchase
        $credit = $purchase->total_amount;

        // Create a ledger entry for the purchase (credit)
        $ledger = SupplierLedger::create([
            'supplier_id' => $purchase->supplier_id,
            'transaction_date' => now(),
            'debit' => 0,
            'credit' => $credit,
            'balance' => $this->calculateSupplierBalance($purchase->supplier_id),
            'remarks' => 'Purchase Order #'.$purchase->id
        ]);

        return $ledger;
    }

    public function addPaymentLedgerEntry(Payment $payment)
    {
        // Calculate the debit entry based on the payment amount
        $debit = $payment->amount;

        // Create a ledger entry for the payment (debit)
        $ledger = SupplierLedger::create([
            'supplier_id' => $payment->supplier_id,
            'transaction_date' => now(),
            'debit' => $debit,
            'credit' => 0,
            'balance' => $this->calculateSupplierBalance($payment->supplier_id),
            'remarks' => 'Payment to Supplier #'.$payment->payment_id
        ]);

        return $ledger;
    }

    private function calculateSupplierBalance($supplierId)
    {
        // Calculate the running balance of the supplier
        $entries = SupplierLedger::where('supplier_id', $supplierId)->get();
        $balance = $entries->sum('credit') - $entries->sum('debit');
        return $balance;
    }

    public function getSupplierLedger($supplierId)
    {
        return SupplierLedger::where('supplier_id', $supplierId)
            ->orderBy('transaction_date', 'desc')
            ->get();
    }
}
