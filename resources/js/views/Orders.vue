<script>
    export default {
        data() {
            return {
                edit: false,
                edit_order: {},
                orders: [],
                allProducts: [],
                formData: {
                    products: []
                }
            }
        },

        beforeMount() {
          this.getOrders();
        },

        methods: {
            resetData() {
                this.formData.products = [];
                this.edit =false;
            },

            getOrders() {
                axios.get('/api/orders').then(response => {
                    this.orders = response.data.orders;
                })
            },

            getProducts() {
                axios.get('/api/products').then(response => {
                    console.log(response);
                    this.allProducts = response.data.products;
                })
            },

            storeOrder(last = true) {
                axios.post('/api/orders', this.formData).then(response => {
                    console.log(response);
                    this.orders.unshift(response.data.order);
                    this.resetData();
                    if (last) {
                        this.hideModal()
                    }
                });
            },

            editOrder(orderID) {
                axios.get(`/api/orders/${orderID}`).then(response => {
                    this.edit_order = response.data.order;
                    this.edit = true;
                    this.formData.products = [];
                    this.showModal();
                });
            },

            updateOrder(orderID) {
                axios.patch(`/api/orders/${orderID}`, this.formData).then(response => {
                    this.resetData();
                    this.getOrders();
                    this.hideModal();
                });
            },

            showModal() {
                this.getProducts();
                $('#orderModal').modal('show')
            },

            hideModal() {
                $('#orderModal').modal('hide')
            }
        }
    }
</script>

<template>
    <!-- orders list -->
    <div class="card">
        <div class="card-header text-center">
            <h3>My Orders</h3>
        </div>
        <div class="card-body">
            <div class="text-right my-2">
                <button type="button" class="btn btn-primary" @click.prevent="showModal">
                    <i class="fas fa-pus-cricle"></i> Order
                </button>
            </div>
            <div v-if="orders.length" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(order, i) in orders" :key="`${i}-${order.id}`">
                            <td>{{ order.order_number }}</td>
                            <td>{{ order.date_created }}</td>
                            <td>
                                <button class="btn btn-primary" @click.prevent="editOrder(orderID = order.id)">Edit</button>
                                <button class="btn btn-danger" @click.prevent="editOrder(orderID = order.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have orders yet...</p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel">
                            <span v-if="edit">Edit Order</span>
                            <span v-else>Add Order</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Products</label>
                                <div class="form-check" v-for="(product, i) in allProducts" :key="`${i}-${product.id}`">
                                    <input :id="product.id" :value="product.id" name="products" type="checkbox" v-model="formData.products" />
                                    <label class="form-check-label" :for="product.id">{{ product.name }}</label>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <span v-if="edit">
                                <button type="button" class="btn btn-primary" @click.prevent="updateOrder(orderID = edit_order.id)">Update</button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary" @click.prevent="storeOrder(last = false)">Save & Add More</button>
                                <button type="button" class="btn btn-success" @click.prevent="storeOrder">Save & Exit</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
