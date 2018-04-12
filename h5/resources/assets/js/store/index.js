import Vue from 'vue';
import Vuex from 'vuex';

import shop from "./modules/shop";
import pay from "./modules/pay";
import sms from "./modules/sms";
import member from "./modules/member";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        shop,
        pay,
        sms,
        member
    },
    strict: process.env.NODE_ENV !== 'production'
});