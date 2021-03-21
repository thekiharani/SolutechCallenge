<script>
    export default {
        components: {},

        data() {
            return {
                edit: false,
                edit_product: {},
                products: [],
                viewProduct: {},
                formData: {
                    name: '',
                    quantity: 1,
                    description: ''
                }
            }
        },

        beforeMount() {
          this.getProducts();
        },

        methods: {
            resetData() {
                this.formData.name = '';
                this.formData.quantity = 1;
                this.formData.description = '';
                this.edit_product = {};
                this.edit =false;
            },

            getProducts() {
                axios.get('/api/products').then(response => {
                    this.products = response.data.products;
                }).catch(error => {
                    this.showToast('error', 'An error occurred in getting products')
                });
            },

            showProduct(productID) {
                axios.get(`/api/products/${productID}`).then(response => {
                    this.viewProduct = response.data.product;
                    this.showProductModal();
                }).catch(error => {
                    this.showToast('error', 'Error in getting product')
                });
            },

            storeProduct() {
                axios.post('/api/products', this.formData).then(response => {
                    this.products.unshift(response.data.product);
                    this.resetData();
                    this.hideModal();
                    this.showToast('success', 'Product was created');
                }).catch(error => {
                    this.showToast('error', 'An error occurred in creating product')
                });
            },

            editProduct(productID) {
                axios.get(`/api/products/${productID}`).then(response => {
                    this.edit_product = response.data.product;
                    this.edit = true;
                    this.formData.name = this.edit_product.name;
                    this.formData.quantity = this.edit_product.quantity;
                    this.formData.description = this.edit_product.description;
                    this.showModal();
                });
            },

            updateProduct(productID) {
                axios.patch(`/api/products/${productID}`, this.formData).then(response => {
                    this.resetData();
                    this.getProducts();
                    this.hideModal();
                    this.showToast('success', 'Product was updated');
                }).catch(error => {
                    this.showToast('error', 'An error occurred in updating product')
                });
            },

            trashProduct(productID) {
                this.$swal({
                    title: 'Before we Proceed',
                    text: 'Are you sure you want to trash this product?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'No, Cancel',
                    confirmButtonText: 'Yes, Proceed'
                }).then(res => {
                    if (res.value) {
                        axios.delete(`/api/products/${productID}`).then(response => {
                            this.getProducts();
                            this.showToast('success', 'Product was trashed');
                        }).catch(error => {
                            this.showToast('error', 'An error occurred in trashing product')
                        });
                    }
                });
            },

            restoreProduct(productID) {
                this.$swal({
                    title: 'Before we Proceed',
                    text: 'Are you sure you want to restore this product?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'No, Cancel',
                    confirmButtonText: 'Yes, Proceed'
                }).then(res => {
                    if (res.value) {
                        axios.patch(`/api/products/${productID}/restore`).then(response => {
                            this.getProducts();
                            this.showToast('success', 'Product was restored');
                        }).catch(error => {
                            this.showToast('error', 'An error occurred in restoring product')
                        });
                    }
                });
            },

            showModal() {
                $('#productModal').modal('show')
            },

            showProductModal() {
                $('#productDatailsModal').modal('show')
            },

            hideModal() {
                $('#productModal').modal('hide')
            },

            successAlert(message) {
                this.$swal({
                    title: 'Success',
                    text: message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK, Exit'
                });
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
    <!-- products list -->
    <div class="card">
        <div class="card-header text-center">
            <h3>My Products</h3>
        </div>
        <div class="card-body">
            <div class="text-right my-2">
                <button type="button" class="btn btn-primary" @click.prevent="showModal">
                    <i class="fas fa-plus-circle"></i> Product
                </button>
            </div>
            <div v-if="products.length" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, i) in products" :key="`${i}-${product.id}`">
                            <td>{{ product.name }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.date_created }}</td>
                            <td>
                                <button class="btn btn-success btn-sm" :disabled="product.trashed" @click.prevent="editProduct(product.id)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-primary btn-sm" :disabled="product.trashed" @click.prevent="showProduct(product.id)" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button v-if="product.trashed" class="btn btn-secondary btn-sm" @click.prevent="restoreProduct(product.id)" title="Restore">
                                    <i class="fas fa-trash-restore-alt"></i>
                                </button>
                                <button v-else class="btn btn-danger btn-sm" @click.prevent="trashProduct(product.id)" title="Trash">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have products yet...</p>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="productModalLabel">
                            <span v-if="edit">Edit Product</span>
                            <span v-else>Add Product</span>
                        </h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Product Name" v-model="formData.name">
                            </div>

                            <div class="form-group">
                                <label for="quantity">Product Quantity</label>
                                <input type="number" min="1" name="quantity" id="quantity" class="form-control" placeholder="Product Quantity" v-model="formData.quantity">
                            </div>

                            <div class="form-group">
                                <label for="description">Product Description</label>
                                <textarea rows="4" name="description" id="description" class="form-control" placeholder="Product Description" v-model="formData.description"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                Close <i class="fas fa-times-circle"></i>
                            </button>
                            <span v-if="edit">
                                <button type="button" class="btn btn-primary" @click.prevent="updateProduct(productID = edit_product.id)">
                                    Update <i class="fas fa-check-circle"></i>
                                </button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary" @click.prevent="storeProduct">
                                    Create <i class="fas fa-check-circle"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details Modal -->
        <div class="modal fade" id="productDatailsModal" tabindex="-1" aria-labelledby="productDatailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="productDatailsModalLabel">
                            Product Details
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
                                        <td>Name</td>
                                        <th>{{ viewProduct.name }}</th>
                                    </tr>
                                    <tr>
                                        <td>Quantity</td>
                                        <th>{{ viewProduct.quantity }}</th>
                                    </tr>
                                    <tr>
                                        <td>Date Created</td>
                                        <th>{{ viewProduct.date_created }}</th>
                                    </tr>
                                    <tr>
                                        <td>Last Updated</td>
                                        <th>{{ viewProduct.last_updated }}</th>
                                    </tr>
                                    <tr>
                                        <td>List Suppliers</td>
                                        <th>{{ viewProduct.listedSuppliers }}</th>
                                    </tr>
                                    <tr>
                                        <td>Placed Orders</td>
                                        <th>{{ viewProduct.placedOrders }}</th>
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <th>{{ viewProduct.description }}</th>
                                    </tr>
                                </tbody>
                            </table>

                            <div v-if="viewProduct.orders">
                                <h3>Orders Placed</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Date Placed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="order in viewProduct.orders" :key="order.id">
                                            <td>{{  order.order_number }}</td>
                                            <td>{{ order.date_created }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div v-if="viewProduct.suppliers">
                                <h3>Listed Suppliers</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date Listed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="supplier in viewProduct.suppliers" :key="supplier.id">
                                            <td>{{  supplier.name }}</td>
                                            <td>{{ supplier.date_created }}</td>
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
