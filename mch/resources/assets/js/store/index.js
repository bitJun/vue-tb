import Vue from 'vue';
import Vuex from 'vuex';

//import * as actions from './actions.js';
//import * as mutations from './mutations.js';
import login from "./modules/login";
import authUser from "./modules/auth-user";
import timeline from "./modules/timeline";
import qiniu from "./modules/qiniu";
import region from "./modules/region";
import shop from "./modules/shop";
import member from "./modules/member";
import order from "./modules/order";
import creditDetail from "./modules/credit-detail";
import balanceDetail from "./modules/balance-detail";
import creditRule from "./modules/credit-rule";
import password from "./modules/password";
import bank from "./modules/bank";
import shopBalanceDetail from "./modules/shop-balance-detail";
import shopWithdraw from "./modules/shop-withdraw";
import shopQrcode from "./modules/shop-qrcode";
import tradeSetting from "./modules/trade-setting";
import memberSetting from "./modules/member-setting";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        login,
        authUser,
        timeline,
        qiniu,
        region,
        shop,
        member,
        order,
        creditDetail,
        balanceDetail,
        creditRule,
        password,
        bank,
        shopBalanceDetail,
        shopWithdraw,
        shopQrcode,
        tradeSetting,
        memberSetting
    },
    //actions,
    //mutations,
    strict: process.env.NODE_ENV !== 'production'
});