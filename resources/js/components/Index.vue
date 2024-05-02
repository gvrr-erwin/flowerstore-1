<template>
    <div class="container mt-5">
      <h1 class="mb-4">Items</h1>
      <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
      </div>
      <div class="d-flex justify-content-end mb-4">
        <router-link :to="{ name: 'Create' }" class="btn btn-success">
          <i class="bi bi-plus-lg"></i> Add New Item
        </router-link>
      </div>
        <!-- Search bar -->
        <div class="mb-3">
                <label for="searchInput" class="form-label">Search Item:</label>
                <input type="text" id="searchInput" class="form-control" v-model="search" placeholder="Enter item name">
        </div>
        <!-- table for contents/data -->
      <div class="table-responsive">
        <table class="table table-hover table-bordered">
          <thead class="table-dark">
            <tr>
              <th scope="col"></th>
              <th scope="col">Item Name</th>
              <th scope="col">Item Price</th>
              <th scope="col">Item Type</th>
              <th scope="col">Quantity</th>
              <th scope="col">Shop</th>
              <th scope="col" style="width: 20%;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items" :key="item.id">

              <td><input type="checkbox" v-model="item.checked"></td>
              <td :style="{ 'text-decoration': item.checked ? 'line-through' : 'none' }">{{ item.name }}</td>
              <td :style="{ 'text-decoration': item.checked ? 'line-through' : 'none' }">{{ item.price }}</td>
              <td :style="{ 'text-decoration': item.checked ? 'line-through' : 'none' }"> {{ item.itemType }}</td>
              <td :style="{ 'text-decoration': item.checked ? 'line-through' : 'none' }">{{ item.quantity }}</td>
              <td :style="{ 'text-decoration': item.checked ? 'line-through' : 'none' }">{{ item.shop ? item.shop.name : 'N/A' }}</td>
              <td>
                <router-link :to="{ name: 'Edit', params: { id: item.id } }" class="btn btn-sm btn-outline-primary me-2">
                  <i class="bi bi-pencil-square me-1"></i> Edit
                </router-link>
                <button class="btn btn-sm btn-outline-danger" @click="openDeleteConfirmation(item.id)">
                  <i class="bi bi-trash me-1"></i> Delete
                </button>
              </td>
            </tr>
            <!-- No Items Found Message -->
            <tr v-if="items.length === 0">
                <td colspan="8" class="text-center">No items found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
            aria-hidden="true" ref="deleteModal">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
              <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
              <button type="button" class="btn btn-danger" @click="confirmDelete">Delete</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="pagination-buttons">
        <button @click="previousPage" class="btn btn-primary" :disabled="currentPage === 1">Previous Page</button>
        <button @click="nextPage" class="btn btn-primary" :disabled="currentPage === totalPages">Next Page</button>
      </div>

      <!-- Filter inputs -->
        <div class="row g-3">
            <div class="col-md-6">
                <label for="maxPrice" class="form-label">Max Price:</label>
                <input type="number" id="maxPrice" class="form-control" v-model="maxPrice" @input="filterItems" placeholder="Enter max price">
                <button @click="resetPrice" class="btn btn-primary">Reset</button>
            </div>
            <div class="col-md-6">
                <label class="form-label">Item Type:</label>
                <div class="form-check" v-for="(type, index) in itemTypes" :key="index">
                <input class="form-check-input" type="checkbox" :value="type" :id="type" v-model="selectedItemTypes">
                <label class="form-check-label" :for="type">{{ type }}</label>
                </div>
            </div>
        </div>
        <livewire:shop-component />
      </div>
  </template>

<script>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  setup() {
    const items = ref([]);
    const currentPage = ref(1);
    const maxPrice = ref(null);
    const itemTypes = ref(['meat', 'vegetable', 'fruit', 'drink', 'canned_good', 'others']);
    const selectedItemTypes = ref([]);
    const search = ref('');
    const itemIdToDelete = ref(null);
    const deleteModal = ref(null);
    const successMessage = ref('');
    const router = useRouter();
    const shops = ref([]);
    const totalPages = ref(null);

    const fetchItems = async () => {
        try {
        const response = await axios.get('http://localhost:8000/items', {
          params: {
            max_price: maxPrice.value,
            item_types: selectedItemTypes.value.length ? selectedItemTypes.value : null,
            search: search.value,
            page: currentPage.value,
          },
        });
        // Adding a 'checked' property to each item object
        items.value = response.data.data.map(item => ({ ...item, checked: false }));
        totalPages.value = response.data.last_page;
      } catch (error) {
        console.error('Error fetching items:', error);
      }
    };

    const fetchShops = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/shops');
        shops.value = response.data;
      } catch (error) {
        console.error('Error fetching shops:', error);
      }
    };

    const nextPage = () => {
      if (currentPage.value < totalPages.value) {
        currentPage.value += 1;
        fetchItems();
      }
    };

    const previousPage = () => {
      if (currentPage.value > 1) {
        currentPage.value -= 1;
        fetchItems();
      }
    };

    const resetPrice = () => {
      maxPrice.value = null;
      fetchItems();
    };

    const openDeleteConfirmation = (id) => {
        itemIdToDelete.value = id; // Set itemIdToDelete
        const modalInstance = new bootstrap.Modal(deleteModal.value); // Initialize Bootstrap modal instance
        modalInstance.show();
    };


    const closeModal = () => {
      const modalInstance = bootstrap.Modal.getInstance(deleteModal.value);
      if (modalInstance) {
        modalInstance.hide();
      }
    };

    const confirmDelete = async () => {
        if (itemIdToDelete.value !== null) {
            await deleteItem(itemIdToDelete.value);
            closeModal();
        }
    };


    const deleteItem = async (id) => {
      try {
        const uri = `http://localhost:8000/items/${id}`;
        await axios.delete(uri);
        items.value = items.value.filter((item) => item.id !== id);
        successMessage.value = 'Item deleted successfully!';
        setTimeout(() => {
          successMessage.value = '';
          router.push({ name: 'Index' });
        }, 1000);
      } catch (error) {
        console.error('Error deleting item:', error);
        // Handle error (e.g., show error message)
      }
    };

    onMounted(() => {
      fetchItems();
      fetchShops();
    });

    watch([maxPrice, selectedItemTypes, search], () => {
      currentPage.value = 1;
      fetchItems();
    }, { deep: true });


    return {
        items,
        currentPage,
        maxPrice,
        itemTypes,
        selectedItemTypes,
        search,
        shops,
        totalPages,
        nextPage,
        previousPage,
        resetPrice,
        deleteModal,
        openDeleteConfirmation,
        confirmDelete,
        deleteItem,
        closeModal,
        successMessage
    };
  },
};
</script>



<style>
.pagination-buttons {
margin-bottom: 1rem;
}
</style>
