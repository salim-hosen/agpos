<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAllSuppliers(array $filters)
    {
        $query = Supplier::query();

        // Apply filters
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Paginate results
        return $query->paginate(15);  // 15 items per page, can be changed
    }

    public function createSupplier(array $data)
    {
        return Supplier::create($data);
    }

    public function updateSupplier(Supplier $supplier, array $data)
    {
        $supplier->update($data);
        return $supplier;
    }

    public function deleteSupplier(Supplier $supplier)
    {
        $supplier->delete();
        return ['message' => 'Supplier deleted successfully'];
    }
}
