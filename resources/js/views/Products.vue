<script>
    export default {
        components: {},
        data() {
            return {
                action: '',
                store: false,
                edit_product: {},
                products: [],
                suppliers: [],
                orders: [],
                formData: {
                    name: '',
                    quantity: null,
                    description: ''
                }
            }
        },
        beforeMount() {
          this.getProducts();
        },
        methods: {
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
            storeProduct() {
                axios.post('/api/products', this.formData).then(response => {
                    console.log(response);
                    this.products.unshift(response.data.product);
                    this.formData.name = '';
                    this.formData.quantity = null;
                    this.formData.description = '';
                    this.store =false;
                });
            },
            getAction(e) {
                this.checkedContacts = [];
                this.action = e.target.value;
                if (e.target.value === 'add_product') {
                    this.getSuppliers();
                }
                console.log(e.target.value)
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
            <div v-if="products.length" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, i) in products" :key="`${i}-${product.id}`">
                            <td>{{ product.name }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.description }}</td>
                            <td>{{ product.date_created }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have products yet...</p>
            </div>
        </div>
    </div>
</template>
