import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueToast from 'vue-toast-notification';
// import 'vue-toast-notification/dist/theme-default.css';
import 'vue-toast-notification/dist/theme-sugar.css';
import router from './router';
import App from "./App";

require('./bootstrap');
Vue.use(VueSweetalert2);
Vue.use(VueToast);
const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router
});
