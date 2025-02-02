<script setup>
import { ref, onMounted } from "vue";
import Dropdown from "primevue/dropdown";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import apiClient from "@/utils/apiClient";

const suppliers = ref([]);
const products = ref([]);
const purchase = ref({
  supplier_id: null,
  items: [],
});

// Fetch suppliers and products
const fetchData = async () => {
  try {
    const supplierRes = await apiClient.get("/suppliers");
    suppliers.value = supplierRes.data.data;
    
    const productRes = await apiClient.get("/products");
    products.value = productRes.data.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

onMounted(fetchData);

// Add a new empty product row
const addItem = () => {
  purchase.value.items.push({ product_id: null, quantity: 1, unit_price: 0 });
};

// Remove a product row
const removeItem = (index) => {
  purchase.value.items.splice(index, 1);
};

// Submit purchase order
const submitPurchase = async () => {
  try {
    await apiClient.post("/purchases", purchase.value);
    alert("Purchase Order Created Successfully!");
    purchase.value = { supplier_id: null, items: [] }; // Reset form
  } catch (error) {
    console.error("Error submitting purchase:", error);
  }
};
</script>

<template>
  <div class="p-4">
    <h2 class="text-lg font-bold mb-4">Create Purchase Order</h2>
    
    <div class="mb-4">
      <label>Supplier</label>
      <Dropdown v-model="purchase.supplier_id" :options="suppliers" optionLabel="name" optionValue="id" placeholder="Select a Supplier" class="w-full" />
    </div>

    <h3 class="text-md font-semibold mb-2">Purchase Items</h3>
    <div v-for="(item, index) in purchase.items" :key="index" class="grid grid-cols-3 gap-2 mb-2">
      <Dropdown v-model="item.product_id" :options="products" optionLabel="name" optionValue="id" placeholder="Select Product" class="w-full" />
      <InputText type="number" v-model="item.quantity" class="w-full" placeholder="Quantity" />
      <InputText type="number" v-model="item.unit_price" class="w-full" placeholder="Unit Price" />
      <Button icon="pi pi-trash" class="p-button-danger p-button-sm" @click="removeItem(index)" />
    </div>

    <Button label="Add Product" icon="pi pi-plus" class="p-button-sm mb-3" @click="addItem" />
    <Button label="Submit Purchase" icon="pi pi-check" class="p-button-primary w-full" @click="submitPurchase" />
  </div>
</template>
