<script>
    export default {
        components: {},

        data() {
            return {
                dashboardData: [],
            }
        },

        beforeMount() {
            this.getDashboardData();
        },

        methods: {
            getDashboardData() {
                axios.get('/api').then(response => {
                    this.dashboardData = response.data;
                }).catch(error => {
                    this.showToast('error', 'An error occurred in getting dashboard data')
                });
            },
        }
    }
</script>

<template>
    <!-- products list -->
    <div class="card">
        <div class="card-header text-center">
            <h3>Dashboard</h3>
        </div>
        <div class="card-body">
            <div class="row">
			    <div class="col-md-4 col-sm-2 col-xs-12">
					<div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-chart-line fa-5x" aria-hidden="true"></i>
                            <h5 class="my-3">{{ dashboardData.productCount }} <small>Listed Products</small></h5>
                            <router-link to="products" class="btn btn-primary btn-block">View More</router-link>
                        </div>
					 </div>
				</div>

                <div class="col-md-4 col-sm-2 col-xs-12">
					<div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-luggage-cart fa-5x" aria-hidden="true"></i>
                            <h5 class="my-3">{{ dashboardData.supplierCount }} <small>Listed Suppliers</small></h5>
                            <router-link to="suppliers" class="btn btn-primary btn-block">View More</router-link>
                        </div>
					 </div>
				</div>

                <div class="col-md-4 col-sm-2 col-xs-12">
					<div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-cart-arrow-down fa-5x" aria-hidden="true"></i>
                            <h5 class="my-3">{{ dashboardData.orderCount }} <small>Orders Placed</small></h5>
                            <router-link to="orders" class="btn btn-primary btn-block">View More</router-link>
                        </div>
					 </div>
				</div>
            </div>

        </div>

    </div>
</template>
