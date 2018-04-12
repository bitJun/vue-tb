
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
import router from './routes.js';
import App from './components/App.vue'
import store from './store';
import MintUI from 'mint-ui'
import { Toast } from 'mint-ui';
import 'mint-ui/lib/style.css'

Vue.use(MintUI);

axios.interceptors.request.use(config => {
    config.headers['X-CSRF-TOKEN'] = window.Modian.csrfToken;
    config.headers['X-Requested-With'] = 'XMLHttpRequest';
    return config;
}, error => {
    return Promise.reject(error);
});

axios.interceptors.response.use(response => {
    return response;
}, error => {
    if(error.response.status == 500) {
        Toast('抱歉，请稍后再试！');
    }
    if(error.response.status == 400) {
        if(error.response.data.message) {
            Toast(error.response.data.message);
        }
    }
    return Promise.reject(error);
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

const app = new Vue(Vue.util.extend({ router, store},App)).$mount('#app');