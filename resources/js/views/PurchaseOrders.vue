<script setup>
import { ref, onMounted } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import apiClient from "@/utils/apiClient";

const purchases = ref([]);
const totalRecords = ref(0);
const rowsPerPage = ref(10);
const currentPage = ref(1);
const loading = ref(false);

const viewDialog = ref(false);
const selectedPurchase = ref(null);

// Fetch purchases
const fetchPurchases = async () => {
  loading.value = true;
  try {
    const response = await apiClient.get("/purchases", {
      params: { page: currentPage.value, per_page: rowsPerPage.value },
    });
    purchases.value = response.data.data;
    totalRecords.value = response.data.total;
  } catch (error) {
    console.error("Error fetching purchases:", error);
  }
  loading.value = false;
};

// View purchase details
const viewPurchase = (purchase) => {
  selectedPurchase.value = purchase;
  viewDialog.value = true;
};

onMounted(fetchPurchases);
</script>

<template>
  <div class="p-4">
    <h2 class="text-lg font-bold mb-4">Purchase Orders</h2>

    <DataTable :value="purchases" :loading="loading">
      <Column field="id" header="ID"></Column>
      <Column field="supplier.name" header="Supplier"></Column>
      <Column field="total_amount" header="Total Amount"></Column>
      <Column field="purchase_date" header="Date"></Column>
      <Column header="Actions">
        <template #body="slotProps">
          <Button label="View" icon="pi pi-eye" class="p-button-sm p-button-primary" @click="viewPurchase(slotProps.data)" />
        </template>
      </Column>
    </DataTable>

    <Paginator
      :rows="rowsPerPage"
      :totalRecords="totalRecords"
      @page="(e) => (currentPage = e.page + 1)"
    />

    <!-- View Purchase Details Dialog -->
    <Dialog v-model:visible="viewDialog" header="Purchase Details" modal>
      <div v-if="selectedPurchase">
        <p><strong>Supplier:</strong> {{ selectedPurchase.supplier.name }}</p>
        <p><strong>Total Amount:</strong> {{ selectedPurchase.total_amount }}</p>
        <p><strong>Date:</strong> {{ selectedPurchase.created_at }}</p>

        <h3 class="text-md font-semibold mt-4">Items</h3>
        <ul class="list-disc ml-6">
          <li v-for="item in selectedPurchase.items" :key="item.id">
            {{ item.product.name }} - Qty: {{ item.quantity }} @ {{ item.unit_price }}
          </li>
        </ul>
      </div>
      <template #footer>
        <Button label="Close" icon="pi pi-times" class="p-button-text" @click="viewDialog = false" />
      </template>
    </Dialog>
  </div>
</template>
