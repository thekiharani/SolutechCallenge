<template>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script>
    import Products from "./views/Products";
    import Orders from "./views/Orders";
    import Suppliers from "./views/Suppliers";
    export default {
        name: 'App',
        components: {Products, Orders, Suppliers},
    }
</script>
