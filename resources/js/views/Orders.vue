<script>
    export default {
        data() {
            return {
                action: '',
                store: false,
                orders: [],
                formData: {
                    products: [],
                    order_number: ''
                }
            }
        },
        beforeMount() {
          this.getOrders();
        },
        methods: {
            getOrders() {
                axios.get('/api/orders').then(response => {
                    console.log(response);
                    this.orders = response.data.orders;
                })
            },
            storeOrders() {
                axios.post('/api/orders', this.formData).then(response => {
                    console.log(response);
                    this.orders.unshift(response.data.order);
                    this.formData.products = [];
                    this.store =false;
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
            <div v-if="orders.length" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(order, i) in orders" :key="`${i}-${order.id}`">
                            <td>{{ order.order_number }}</td>
                            <td>{{ order.date_created }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div v-else>
                <p class="text-danger text-center">You do not have orders yet...</p>
            </div>
        </div>
    </div>
</template>
