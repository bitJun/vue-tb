
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
//import VueProgressBar from 'vue-progressbar';
//import VeeValidate,{Validator} from 'vee-validate';
import zh from 'vee-validate/dist/locale/zh_CN';
import iView from 'iview';
import 'iview/dist/styles/iview.css';

import router from './routes.js';
import store from './store';
import App from './components/App.vue';
import jwtToken from './helpers/jwt-token';

import AsyncComputed from 'vue-async-computed'

Vue.use(AsyncComputed);
Vue.use(iView);

axios.interceptors.request.use(config => {
    console.log('config!!!');
    config.headers['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
    config.headers['X-Requested-With'] = 'XMLHttpRequest';

    if(jwtToken.getToken()) {
        config.headers['Authorization'] = 'Bearer '+ jwtToken.getToken();
    }

    return config;
}, error => {
    console.log('reject!!!');
    return Promise.reject(error);
});

axios.interceptors.response.use(response => {
    return response;
}, error => {
    let errorResponseData = error.response.data;
    if(error.response.status == 500) {
        iView.Message.error('系统打烊了，请稍后再试!');
    }
    if(error.response.status == 422) {
        if(error.response.data.message) {
            iView.Message.warning(error.response.data.message);
        }
    }
    if(errorResponseData.error && (errorResponseData.error === "token_invalid" || errorResponseData.error === "token_expired" || errorResponseData.error === 'token_not_provided')) {
        store.dispatch('logoutRequest')
            .then(()=> {
                router.push({path: '/login'});
            });
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