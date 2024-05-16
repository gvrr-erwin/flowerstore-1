<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h1 class="card-title">Create An Item</h1>
            </div>
            <div class="card-body">
                <div v-if="successMessage" class="alert alert-success">
                    {{ successMessage }}
                </div>
                <form @submit.prevent="addItem">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="itemName" class="form-label">Item Name:</label>
                            <input type="text" class="form-control" id="itemName" v-model="item.name"
                                placeholder="Enter item name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="itemPrice" class="form-label">Item Price:</label>
                            <input type="text" class="form-control" id="itemPrice" v-model="item.price"
                                placeholder="Enter item price" required>
                        </div>
                        <div class="col-md-6">
                            <label for="itemType" class="form-label">Item Type:</label>
                            <select id="itemType" class="form-select" v-model="item.itemType">
                                <option v-for="type in itemTypes" :key="type" :value="type">{{ type }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="quantity" v-model="item.quantity"
                                placeholder="Enter Quantity" required>
                        </div>
                        <div class="col-md-6">
                            <label for="shop" class="form-label">Shop:</label>
                            <select id="shop_id" class="form-select" v-model="item.shop_id" required>
                                <option value="">Select Shop</option>
                                <option v-for="shop in shops" :key="shop.id" :value="shop.id">{{ shop.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus-lg"></i> Add Item</button>
                    </div>
                </form>
                <div class="mt-4 d-flex justify-content-start">
                    <router-link :to="{ name: 'Index' }" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Return to Items List
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>


<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}
</style>

<script>
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { route } from '../../../vendor/tightenco/ziggy/dist';
import axios from 'axios';

export default {
    setup() {
        const item = reactive({ name: '', price: '', itemType: '', quantity: '', shop_id: '' });
        const router = useRouter();
        const successMessage = ref('');
        const itemTypes = ref([]);
        const shops = ref([]); // Add a ref to store shops

        const addItem = async () => {
            const uri = route('items.store'); // Ensure you have the correct route name
            try {
                await axios.post(uri, item);
                successMessage.value = 'Item added successfully!';
                setTimeout(() => {
                    successMessage.value = '';
                    router.push({ name: 'Index' });
                }, 1000);
            } catch (error) {
                console.error('Error adding item:', error);
                // Handle the error appropriately
            }
        };

        const fetchItemTypes = async () => {
            try {
                const uri = route('items.index');
                const response = await axios.get(uri);
                console.log('Response data:', response.data);

                const itemTypeSet = new Set();
                response.data.data.forEach(item => {
                    itemTypeSet.add(item.itemType);
                });

                itemTypes.value = Array.from(itemTypeSet);
            } catch (error) {
                console.error('Error fetching item types:', error);
                // Handle the error appropriately
            }
        };

        const fetchShops = async () => {
            try {
                const uri = route('shops.index'); // Ensure you have the correct route name for shops
                const response = await axios.get(uri);
                shops.value = response.data;
            } catch (error) {
                console.error('Error fetching shops:', error);
                // Handle the error appropriately
            }
        };

        onMounted(() => {
            fetchItemTypes();
            fetchShops(); // Fetch shops on mount
        });

        return {
            item,
            addItem,
            successMessage,
            itemTypes,
            shops // Return shops so it can be used in the template
        };
    }
}
</script>



