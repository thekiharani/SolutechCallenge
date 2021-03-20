<script>
    export default {
        components: {},
        data() {
            return {
                action: '',
                store: false,
                suppliers: [],
                formData: {
                    name: '',
                    date_created: ''
                }
            }
        },
        beforeMount() {
          this.getSuppliers();
        },
        methods: {
            getSuppliers() {
                axios.get('/api/suppliers').then(response => {
                    console.log(response);
                    this.suppliers = response.data.suppliers;
                })
            },
            storeSupplier() {
                axios.post('/api/suppliers', this.formData).then(response => {
                    console.log(response);
                    this.suppliers.unshift(response.data);
                    this.formData.name = '';
                    this.formData.date_created = '';
                    this.store =false;
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
            <div v-if="suppliers.length" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(supplier, i) in suppliers" :key="`${i}-${supplier.id}`">
                            <td>{{ supplier.name }}</td>
                            <td>{{ supplier.date_created }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have suppliers yet...</p>
            </div>
        </div>
    </div>
</template>
