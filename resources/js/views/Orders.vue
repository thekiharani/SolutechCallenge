<script>
    export default {
        data() {
            return {
                edit: false,
                edit_order: {},
                viewOrder: {},
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
                this.edit_order = {};
                this.formData.products = [];
                this.edit =false;
            },

            getOrders() {
                axios.get('/api/orders').then(response => {
                    this.orders = response.data.orders;
                }).catch(error => {
                    this.showToast('error', 'Error in getting orders')
                });
            },

            showOrder(orderID) {
                axios.get(`/api/orders/${orderID}`).then(response => {
                    this.viewOrder = response.data.order;
                    this.showOrderModal();
                }).catch(error => {
                    this.showToast('error', 'Error in getting order')
                });
            },

            getProducts() {
                axios.get('/api/products').then(response => {
                    console.log(response);
                    this.allProducts = response.data.products;
                }).catch(error => {
                    this.showToast('error', 'Error in getting products')
                });
            },

            storeOrder() {
                axios.post('/api/orders', this.formData).then(response => {
                    console.log(response);
                    this.orders.unshift(response.data.order);
                    this.resetData();
                    this.hideModal();
                    this.showToast('success', 'Order was created');
                }).catch(error => {
                    this.showToast('error', 'Error in placing order')
                });
            },

            editOrder(orderID) {
                axios.get(`/api/orders/${orderID}`).then(response => {
                    this.edit_order = response.data.order;
                    this.edit = true;
                    this.formData.products = this.edit_order.productIDs;
                    this.showModal();
                }).catch(error => {
                    this.showToast('error', 'Error in getting order')
                });
            },

            updateOrder(orderID) {
                axios.patch(`/api/orders/${orderID}`, this.formData).then(response => {
                    this.resetData();
                    this.getOrders();
                    this.hideModal();
                    this.showToast('success', 'Order was updted');
                }).catch(error => {
                    this.showToast('error', 'Error in updating order')
                });
            },

            trashOrder(orderID) {
                this.$swal({
                    title: 'Before we Proceed',
                    text: 'Are you sure you want to trash this order?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'No, Cancel',
                    confirmButtonText: 'Yes, Proceed'
                }).then(res => {
                    if (res.value) {
                        axios.delete(`/api/orders/${orderID}`).then(response => {
                            this.getOrders();
                            this.showToast('success', 'Order was trashed');
                        }).catch(error => {
                            this.showToast('error', 'An error occurred in trashing order')
                        });
                    }
                });
            },

            restoreOrder(orderID) {
                this.$swal({
                    title: 'Before we Proceed',
                    text: 'Are you sure you want to restore this order?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'No, Cancel',
                    confirmButtonText: 'Yes, Proceed'
                }).then(res => {
                    if (res.value) {
                        axios.patch(`/api/orders/${orderID}/restore`).then(response => {
                            this.getOrders();
                            this.showToast('success', 'Order was restored');
                        }).catch(error => {
                            this.showToast('error', 'An error occurred in restoring order')
                        });
                    }
                });
            },

            showModal() {
                this.getProducts();
                $('#orderModal').modal('show')
            },

            showOrderModal() {
                $('#orderDatailsModal').modal('show')
            },

            hideModal() {
                $('#orderModal').modal('hide')
            },

            showToast(type, message) {
                this.$toast.open({
                    message: message,
                    type: type,
                    position: 'top-right',
                    showProgress: true
                });
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
                    <i class="fas fa-plus-circle"></i> Order
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
                                <button class="btn btn-success" :disabled="order.trashed" @click.prevent="editOrder(order.id)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-primary" :disabled="order.trashed" @click.prevent="showOrder(order.id)" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button v-if="order.trashed" class="btn btn-secondary" @click.prevent="restoreOrder(order.id)" title="Restore">
                                    <i class="fas fa-trash-restore-alt"></i>
                                </button>
                                <button v-else class="btn btn-danger" @click.prevent="trashOrder(order.id)" title="Trash">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have orders yet...</p>
            </div>
        </div>

        <!-- Create/Edit Modal -->
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
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                Close <i class="fas fa-times-circle"></i>
                            </button>
                            <span v-if="edit">
                                <button type="button" class="btn btn-primary" @click.prevent="updateOrder(orderID = edit_order.id)">
                                    Update <i class="fas fa-check-circle"></i>
                                </button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary" @click.prevent="storeOrder">
                                    Create <i class="fas fa-check-circle"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details Modal -->
        <div class="modal fade" id="orderDatailsModal" tabindex="-1" aria-labelledby="orderDatailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="orderDatailsModalLabel">
                            Order Details
                        </h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <div class="modal-body">

                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Order Number</td>
                                        <th>{{ viewOrder.order_number }}</th>
                                    </tr>
                                    <tr>
                                        <td>Date Created</td>
                                        <th>{{ viewOrder.date_created }}</th>
                                    </tr>
                                    <tr>
                                        <td>Last Updated</td>
                                        <th>{{ viewOrder.last_updated }}</th>
                                    </tr>
                                    <tr>
                                        <td>Products</td>
                                        <th>{{ viewOrder.orderProducts }}</th>
                                    </tr>
                                </tbody>
                            </table>

                            <div v-if="viewOrder.products">
                                <h3>Orders Products</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="product in viewOrder.products" :key="product.id">
                                            <td>{{  product.name }}</td>
                                            <td>{{ product.date_created }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Close <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
