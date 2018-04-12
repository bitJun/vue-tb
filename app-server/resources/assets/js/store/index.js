import Vue from 'vue';
import Vuex from 'vuex';
import register from './modules/register';
import regions from './modules/regions'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        userId: localStorage.getItem('userId')
    },
    getters: {
        getUser: state => {
            return state.userId
        }
    },
    modules: {
        register,
        regions
    },
    strict: process.env.NODE_ENV !== 'production'
});