import Vue from 'vue';
import Vuex from 'vuex';
import login from './modules/login';
import authUser from "./modules/auth-user";
import facilitator from "./modules/facilitator";
import setting from './modules/setting';
import shop from './modules/shop';
import moker from './modules/moker';
import trade from './modules/trade'
import region from './modules/region'
import qiniu from "./modules/qiniu";
import index from "./modules/index";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {

    },
    getters: {

    },
    modules: {
        login,
        authUser,
        facilitator,
        setting,
        shop,
        moker,
        region,
        qiniu,
        trade,
        index
    },
    strict: process.env.NODE_ENV !== 'production'
});