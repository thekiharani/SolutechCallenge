<script>
    export default {
        components: {},

        data() {
            return {
                edit: false,
                edit_supplier: {},
                viewSupplier: {},
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
                this.edit = false;
                this.edit_supplier = {};
            },

            getSuppliers() {
                axios.get('/api/suppliers').then(response => {
                    this.suppliers = response.data.suppliers;
                }).catch(error => {
                    this.showToast('error', 'Error in getting suppliers')
                });
            },

            showSupplier(supplierID) {
                axios.get(`/api/suppliers/${supplierID}`).then(response => {
                    this.viewSupplier = response.data.supplier;
                    this.showSupplierModal();
                }).catch(error => {
                    this.showToast('error', 'Error in getting supplier')
                });
            },

            getProducts() {
                axios.get('/api/products').then(response => {
                    this.allProducts = response.data.products;
                }).catch(error => {
                    this.showToast('error', 'Error in getting products')
                });
            },

            storeSupplier() {
                axios.post('/api/suppliers', this.formData).then(response => {
                    this.suppliers.unshift(response.data.supplier);
                    this.resetData();
                    this.hideModal();
                    this.showToast('success', 'Supplier was created');
                }).catch(error => {
                    this.showToast('error', 'Error in creating supplier')
                });
            },

            editSupplier(supplierID) {
                axios.get(`/api/suppliers/${supplierID}`).then(response => {
                    this.edit_supplier = response.data.supplier;
                    this.edit = true;
                    this.formData.name = this.edit_supplier.name;
                    this.formData.products = this.edit_supplier.productIDs;
                    this.showModal();
                }).catch(error => {
                    this.showToast('error', 'Error in getting supplier')
                });
            },

            updateSupplier(supplierID) {
                axios.patch(`/api/suppliers/${supplierID}`, this.formData).then(response => {
                    this.resetData();
                    this.getSuppliers();
                    this.hideModal();
                    this.showToast('success', 'Supplier was updated');
                }).catch(error => {
                    this.showToast('error', 'Error in updating supplier')
                });
            },

            trashSupplier(supplierID) {
                this.$swal({
                    title: 'Before we Proceed',
                    text: 'Are you sure you want to trash this supplier?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'No, Cancel',
                    confirmButtonText: 'Yes, Proceed'
                }).then(res => {
                    if (res.value) {
                        axios.delete(`/api/suppliers/${supplierID}`).then(response => {
                            this.getSuppliers();
                            this.showToast('success', 'Supplier was trashed');
                        }).catch(error => {
                            this.showToast('error', 'An error occurred in trashing supplier')
                        });
                    }
                });
            },

            restoreSupplier(supplierID) {
                this.$swal({
                    title: 'Before we Proceed',
                    text: 'Are you sure you want to restore this supplier?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'No, Cancel',
                    confirmButtonText: 'Yes, Proceed'
                }).then(res => {
                    if (res.value) {
                        axios.patch(`/api/suppliers/${supplierID}/restore`).then(response => {
                            this.getSuppliers();
                            this.showToast('success', 'Supplier was restored');
                        }).catch(error => {
                            this.showToast('error', 'An error occurred in restoring supplier')
                        });
                    }
                });
            },

            showModal() {
                this.getProducts();
                $('#supplierModal').modal('show')
            },

            showSupplierModal() {
                $('#orderDatailsModal').modal('show')
            },

            hideModal() {
                $('#supplierModal').modal('hide')
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
    <!-- suppliers list -->
    <div class="card">
        <div class="card-header text-center">
            <h3>My Suppliers</h3>
        </div>
        <div class="card-body">
            <div class="text-right my-2">
                <button type="button" class="btn btn-primary" @click.prevent="showModal">
                    <i class="fas fa-plus-circle"></i> Supplier
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
                                <button class="btn btn-success" :disabled="supplier.trashed" @click.prevent="editSupplier(supplier.id)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-primary" :disabled="supplier.trashed" @click.prevent="showSupplier(supplier.id)" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button v-if="supplier.trashed" class="btn btn-secondary" @click.prevent="restoreSupplier(supplier.id)" title="Restore">
                                    <i class="fas fa-trash-restore-alt"></i>
                                </button>
                                <button v-else class="btn btn-danger" @click.prevent="trashSupplier(supplier.id)" title="Trash">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have suppliers yet...</p>
            </div>
        </div>

        <!-- Create/Edit Modal -->
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
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                Close <i class="fas fa-times-circle"></i>
                            </button>
                            <span v-if="edit">
                                <button type="button" class="btn btn-primary" @click.prevent="updateSupplier(supplierID = edit_supplier.id)">
                                    Update <i class="fas fa-check-circle"></i>
                                </button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary" @click.prevent="storeSupplier">
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
                            Supplier Details
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
                                        <th>{{ viewSupplier.name }}</th>
                                    </tr>
                                    <tr>
                                        <td>Date Created</td>
                                        <th>{{ viewSupplier.date_created }}</th>
                                    </tr>
                                    <tr>
                                        <td>Last Updated</td>
                                        <th>{{ viewSupplier.last_updated }}</th>
                                    </tr>
                                    <tr>
                                        <td>Products</td>
                                        <th>{{ viewSupplier.supplierProducts }}</th>
                                    </tr>
                                </tbody>
                            </table>

                            <div v-if="viewSupplier.products">
                                <h3>Supplier Products</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="product in viewSupplier.products" :key="product.id">
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
