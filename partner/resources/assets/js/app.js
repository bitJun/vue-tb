
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue';
import router from './routes.js';
import App from './components/App.vue';
import store from './store';
import iView from 'iview';
import jwtToken from './helpers/jwt-token';
import 'iview/dist/styles/iview.css';
import './tool/filiter';

Vue.use(iView);


axios.interceptors.request.use(config => {
  config.headers['X-CSRF-TOKEN'] = window.Modian.csrfToken;
  config.headers['X-Requested-With'] = 'XMLHttpRequest';

  if(jwtToken.getToken()) {
      config.headers['Authorization'] = 'Bearer '+ jwtToken.getToken();
  }

  return config;
}, error => {
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
      console.log(error)
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

//Vue.component('example', require('./components/Example.vue'));

const app = new Vue(Vue.util.extend({ router, store},App)).$mount('#app');