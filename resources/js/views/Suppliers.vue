<script setup>
import { ref, onMounted, watch } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import apiClient from "@/utils/apiClient";

const suppliers = ref([]);
const totalRecords = ref(0);
const rowsPerPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref("");
const loading = ref(false);

const supplierDialog = ref(false);
const deleteDialog = ref(false);
const selectedSupplier = ref(null);
const isEditing = ref(false);
const newSupplier = ref({
  name: "",
  contact_info: "",
  address: "",
});

// Fetch suppliers
const fetchSuppliers = async () => {
  loading.value = true;
  try {
    const response = await apiClient.get("/suppliers", {
      params: { page: currentPage.value, per_page: rowsPerPage.value, name: searchQuery.value },
    });
    suppliers.value = response.data.data;
    totalRecords.value = response.data.total;
  } catch (error) {
    console.error("Error fetching suppliers:", error);
  }
  loading.value = false;
};

// Open modal for creating/editing supplier
const openDialog = (supplier = null) => {
  isEditing.value = !!supplier;
  newSupplier.value = supplier ? { ...supplier } : { name: "", contact_info: "", address: "" };
  supplierDialog.value = true;
};

// Save or update supplier
const saveSupplier = async () => {
  try {
    if (isEditing.value) {
      await apiClient.put(`/suppliers/${newSupplier.value.id}`, newSupplier.value);
    } else {
      await apiClient.post("/suppliers", newSupplier.value);
    }
    supplierDialog.value = false;
    fetchSuppliers();
  } catch (error) {
    console.error("Error saving supplier:", error);
  }
};

// Open delete confirmation dialog
const confirmDelete = (supplier) => {
  selectedSupplier.value = supplier;
  deleteDialog.value = true;
};

// Delete supplier
const deleteSupplier = async () => {
  try {
    await apiClient.delete(`/suppliers/${selectedSupplier.value.id}`);
    deleteDialog.value = false;
    fetchSuppliers();
  } catch (error) {
    console.error("Error deleting supplier:", error);
  }
};

watch([currentPage, searchQuery], fetchSuppliers);
onMounted(fetchSuppliers);
</script>

<template>
  <div class="p-4">
    <!-- Search and Add Supplier -->
    <div class="flex justify-between mb-3">
      <InputText v-model="searchQuery" placeholder="Search suppliers..." class="p-inputtext-sm w-full md:w-80" />
      <Button label="Add Supplier" icon="pi pi-plus" @click="openDialog()" class="p-button-sm" />
    </div>

    <!-- Supplier Table -->
    <DataTable :value="suppliers" :loading="loading">
      <Column field="id" header="ID"></Column>
      <Column field="name" header="Supplier Name"></Column>
      <Column field="contact_info" header="Contact Info"></Column>
      <Column field="address" header="Address"></Column>
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

    <!-- Add/Edit Supplier Dialog -->
    <Dialog v-model:visible="supplierDialog" header="Supplier Details" modal>
      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
          <label>Name</label>
          <InputText v-model="newSupplier.name" class="w-full" />
        </div>
        <div class="col-span-2">
          <label>Contact Info</label>
          <InputText v-model="newSupplier.contact_info" class="w-full" />
        </div>
        <div class="col-span-2">
          <label>Address</label>
          <InputText v-model="newSupplier.address" class="w-full" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="supplierDialog = false" />
        <Button label="Save" icon="pi pi-check" class="p-button-primary" @click="saveSupplier" />
      </template>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:visible="deleteDialog" header="Confirm Delete" modal>
      <p>Are you sure you want to delete <strong>{{ selectedSupplier?.name }}</strong>?</p>
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="deleteDialog = false" />
        <Button label="Delete" icon="pi pi-trash" class="p-button-danger" @click="deleteSupplier" />
      </template>
    </Dialog>
  </div>
</template>
