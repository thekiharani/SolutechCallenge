import Vue from 'vue';
import VueRouter from 'vue-router';
import Products from "./views/Products";
import Suppliers from "./views/Suppliers";
import Orders from "./views/Orders";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {path: '/', component: Products},
        {path: '/products', component: Products},
        {path: '/orders', component: Orders},
        {path: '/suppliers', component: Suppliers}
    ],
    mode: 'history'
});
