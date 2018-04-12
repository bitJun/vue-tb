
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import router from './routes.js';
import store from './store';
import { Toast } from 'mint-ui';
import { Popup } from 'mint-ui';
import App from './components/App.vue';

Vue.component(Popup.name, Popup);

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

/*Vue.use(VueProgressBar, {
 color: '#f60',
 failedColor: 'red',
 height: '2px'
 });*/

/*Validator.addLocale(zh);
 const config = {
 errorBagName: 'veeErrors',
 locale: 'zh_CN',
 };
 Vue.use(VeeValidate, config);

 Validator.updateDictionary({
 zh_CN: {
 messages: {
 required: field => '请输入'+field
 }
 }
 });*/

const app = new Vue(Vue.util.extend({ router,store},App)).$mount('#app');
