<script>
    export default {
        components: {},

        data() {
            return {
                edit: false,
                edit_supplier: {},
                suppliers: [],
                allProducts: [],
                formData: {
                    name: '',
                    products: []
                }
            }
        },

        beforeMount() {
          this.getSuppliers();
        },

        methods: {
            resetData() {
                this.formData.name = '';
                this.formData.products = [];
                this.edit =false;
            },

            getSuppliers() {
                axios.get('/api/suppliers').then(response => {
                    this.suppliers = response.data.suppliers;
                })
            },

            getProducts() {
                axios.get('/api/products').then(response => {
                    console.log(response);
                    this.allProducts = response.data.products;
                })
            },

            storeSupplier(last = true) {
                axios.post('/api/suppliers', this.formData).then(response => {
                    this.suppliers.unshift(response.data.supplier);
                    this.resetData();
                    if (last) {
                        this.hideModal()
                    }
                });
            },

            editSupplier(supplierID) {
                axios.get(`/api/suppliers/${supplierID}`).then(response => {
                    this.edit_supplier = response.data.supplier;
                    this.edit = true;
                    this.formData.name = this.edit_supplier.name;
                    this.formData.products = [];
                    this.showModal();
                });
            },

            updateSupplier(supplierID) {
                axios.patch(`/api/suppliers/${supplierID}`, this.formData).then(response => {
                    this.resetData();
                    this.getSuppliers();
                    this.hideModal();
                });
            },
            showModal() {
                this.getProducts();
                $('#supplierModal').modal('show')
            },

            hideModal() {
                $('#supplierModal').modal('hide')
            }
        }
    }
</script>

<template>
    <!-- suppliers list -->
    <div class="card">
        <div class="card-header text-center">
            <h3>My Suppliers</h3>
        </div>
        <div class="card-body">
            <div class="text-right my-2">
                <button type="button" class="btn btn-primary" @click.prevent="showModal">
                    <i class="fas fa-pus-cricle"></i> Supplier
                </button>
            </div>
            <div v-if="suppliers.length" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(supplier, i) in suppliers" :key="`${i}-${supplier.id}`">
                            <td>{{ supplier.name }}</td>
                            <td>{{ supplier.date_created }}</td>
                            <td>
                                <button class="btn btn-primary" @click.prevent="editSupplier(supplierID = supplier.id)">Edit</button>
                                <button class="btn btn-danger" @click.prevent="editSupplier(supplierID = supplier.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have suppliers yet...</p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supplierModalLabel">
                            <span v-if="edit">Edit Supplier</span>
                            <span v-else>Add Supplier</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Supplier Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Supplier Name" v-model="formData.name">
                            </div>

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
                                <button type="button" class="btn btn-primary" @click.prevent="updateSupplier(supplierID = edit_supplier.id)">Update</button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary" @click.prevent="storeSupplier(last = false)">Save & Add More</button>
                                <button type="button" class="btn btn-success" @click.prevent="storeSupplier">Save & Exit</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
