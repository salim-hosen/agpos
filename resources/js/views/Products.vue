<script setup>
import { ref, onMounted, watch } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import apiClient from "@/utils/apiClient";

const products = ref([]);
const totalRecords = ref(0);
const rowsPerPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref("");
const loading = ref(false);

const productDialog = ref(false);
const deleteDialog = ref(false);
const selectedProduct = ref(null);
const isEditing = ref(false);
const newProduct = ref({
  name: "",
//   category: "",
  sku: "",
  price: "",
  initial_stock_quantity: "",
});

// Fetch products
const fetchProducts = async () => {
  loading.value = true;
  try {
    const response = await apiClient.get("/products", {
      params: { page: currentPage.value, per_page: rowsPerPage.value, name: searchQuery.value },
    });
    products.value = response.data.data;
    totalRecords.value = response.data.total;
  } catch (error) {
    console.error("Error fetching products:", error);
  }
  loading.value = false;
};

// Open modal for creating/editing product
const openDialog = (product = null) => {
  isEditing.value = !!product;
  newProduct.value = product ? { ...product } : {
    name: "",
    // category: "",
    sku: "",
    price: "",
    initial_stock_quantity: ""
  };
  productDialog.value = true;
};

// Save or update product
const saveProduct = async () => {
  try {
    if (isEditing.value) {
      await apiClient.put(`/products/${newProduct.value.id}`, newProduct.value);
    } else {
      await apiClient.post("/products", newProduct.value);
    }
    productDialog.value = false;
    fetchProducts();
  } catch (error) {
    console.error("Error saving product:", error);
  }
};

// Open delete confirmation dialog
const confirmDelete = (product) => {
  selectedProduct.value = product;
  deleteDialog.value = true;
};

// Delete product
const deleteProduct = async () => {
  try {
    await apiClient.delete(`/products/${selectedProduct.value.id}`);
    deleteDialog.value = false;
    fetchProducts();
  } catch (error) {
    console.error("Error deleting product:", error);
  }
};

watch([currentPage, searchQuery], fetchProducts);
onMounted(fetchProducts);
</script>

<template>
  <div class="p-4">
    <!-- Search and Add Product -->
    <div class="flex justify-between mb-3">
      <InputText v-model="searchQuery" placeholder="Search products..." class="p-inputtext-sm w-full md:w-80" />
      <Button label="Add Product" icon="pi pi-plus" @click="openDialog()" class="p-button-sm" />
    </div>

    <!-- Product Table -->
    <DataTable :value="products" :loading="loading">
      <Column field="id" header="ID"></Column>
      <Column field="name" header="Product Name"></Column>
      <Column field="sku" header="SKU"></Column>
      <!-- <Column field="category" header="Category"></Column> -->
      <Column field="price" header="Price"></Column>
      <Column field="initial_stock_quantity" header="Initital Stock Qty"></Column>
      <Column field="current_stock_quantity" header="Current Stock Qty"></Column>
      <Column header="Actions">
        <template #body="slotProps">
          <Button icon="pi pi-pencil" class="p-button-sm p-button-warning mr-2" @click="openDialog(slotProps.data)" />
          <Button icon="pi pi-trash" class="p-button-sm p-button-danger" @click="confirmDelete(slotProps.data)" />
        </template>
      </Column>
    </DataTable>

    <!-- Pagination -->
    <Paginator
      :rows="rowsPerPage"
      :totalRecords="totalRecords"
      @page="(e) => (currentPage = e.page + 1)"
    />

    <!-- Add/Edit Product Dialog -->
    <Dialog v-model:visible="productDialog" header="Product Details" modal>
      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
          <label>Name</label>
          <InputText v-model="newProduct.name" class="w-full" />
        </div>
        <!-- <div>
          <label>Category</label>
          <InputText v-model="newProduct.category" class="w-full" />
        </div> -->
        <div>
          <label>SKU</label>
          <InputText v-model="newProduct.sku" class="w-full" />
        </div>
        <div>
          <label>Price</label>
          <InputText type="number" v-model="newProduct.price" class="w-full" />
        </div>
        <div>
          <label>Initial Stock Qty</label>
          <InputText type="number" v-model="newProduct.initial_stock_quantity" class="w-full" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="productDialog = false" />
        <Button label="Save" icon="pi pi-check" class="p-button-primary" @click="saveProduct" />
      </template>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:visible="deleteDialog" header="Confirm Delete" modal>
      <p>Are you sure you want to delete <strong>{{ selectedProduct?.name }}</strong>?</p>
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="deleteDialog = false" />
        <Button label="Delete" icon="pi pi-trash" class="p-button-danger" @click="deleteProduct" />
      </template>
    </Dialog>
  </div>
</template>
