<script>
    export default {
        components: {},

        data() {
            return {
                edit: false,
                edit_product: {},
                products: [],
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
                this.edit =false;
            },

            getProducts() {
                axios.get('/api/products').then(response => {
                    console.log(response);
                    this.products = response.data.products;
                })
            },

            getSuppliers() {
                axios.get('/api/suppliers').then(response => {
                    console.log(response);
                    this.suppliers = response.data.suppliers;
                })
            },

            storeProduct(last = true) {
                axios.post('/api/products', this.formData).then(response => {
                    this.products.unshift(response.data.product);
                    this.resetData();
                    if (last) {
                        this.hideModal()
                    }
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
                });
            },

            showModal() {
                $('#productModal').modal('show')
            },

            hideModal() {
                $('#productModal').modal('hide')
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
                    <i class="fas fa-pus-cricle"></i> Product
                </button>
            </div>
            <div v-if="products.length" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, i) in products" :key="`${i}-${product.id}`">
                            <td>{{ product.name }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.description }}</td>
                            <td>{{ product.date_created }}</td>
                            <td>
                                <button class="btn btn-primary" @click.prevent="editProduct(productID = product.id)">Edit</button>
                                <button class="btn btn-danger" @click.prevent="editProduct(productID = product.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have products yet...</p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">
                            <span v-if="edit">Edit Product</span>
                            <span v-else>Add Product</span>
                        </h5>
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
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <span v-if="edit">
                                <button type="button" class="btn btn-primary" @click.prevent="updateProduct(productID = edit_product.id)">Update</button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary" @click.prevent="storeProduct(last = false)">Save & Add More</button>
                                <button type="button" class="btn btn-success" @click.prevent="storeProduct">Save & Exit</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
