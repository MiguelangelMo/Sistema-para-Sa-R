/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vista Categoría
Vue.component('categoria', require('./components/Categoria.vue'));
// Vista Artículo
Vue.component('articulo', require('./components/Articulo.vue'));
// Vista Cliente
Vue.component('cliente', require('./components/Cliente.vue'));
// Vista Proveedor
Vue.component('proveedor', require('./components/Proveedor.vue'));
// Vista Roles
Vue.component('rol', require('./components/Rol.vue'));
// Vista Usuario
Vue.component('usuario', require('./components/User.vue'));
// Vista Ingreso
Vue.component('ingreso', require('./components/Ingreso.vue'));
// Vista Venta
Vue.component('venta', require('./components/Venta.vue'));
// Escritorio
Vue.component('dashboard', require('./components/Dashboard.vue'));

const app = new Vue({
    el: '#app',
    data: {
        menu: 0
    }
});