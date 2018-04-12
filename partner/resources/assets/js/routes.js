import Vue from 'vue';
import VueRouter from 'vue-router';
import iView from 'iview';
import jwtToken from './helpers/jwt-token';
import Store from './store'

Vue.use(VueRouter);

import Login from './components/login/login.vue';
import Index from './components/index/index.vue';
import FacilitatorIndex from './components/facilitator/index.vue';
import Facilitator from './components/facilitator/facilitator.vue';
import FacilitatorAdd from './components/facilitator/add.vue';
import FacilitatorEdit from './components/facilitator/edit.vue';
import FacilitatorDetail from './components/facilitator/detail.vue';
import Business from './components/business/index.vue';
import BusinessList from './components/business/list.vue';
import BusinessDetail from './components/business/detail.vue';
import BusinessAdd from './components/business/add.vue';
import BusinessEdit from './components/business/edit.vue';
import Moker from './components/moker/index.vue';
import MokerList from './components/moker/list.vue';
import MokerDetail from './components/moker/detail.vue';
import MokerSetting from './components/moker/setting.vue';
import Setting from './components/setting/index.vue';
import AccountSetting from './components/setting/account.vue';
import PasswordSetting from './components/setting/password.vue';
import WithdrawSetting from './components/setting/withdraw.vue';
import Trade from './components/trade/index.vue';
import TradeList from './components/trade/list.vue';

const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  linkActiveClass: 'active',
  routes: [
    {
      path: '/',
    	name: 'index',
    	component: Index,
      meta: {requiresAuth: true, title: "首页", activeTopMenu: 'index',activeSidebar: 'index' }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: {requiresGuest: true, title: "登录" }
    },
    {
      path: '/facilitator',
      name: 'facilitator',
      component: FacilitatorIndex,
      meta: {requiresAuth: true, title: "服务商" },
      children: [
        {
          path: '/facilitator',
          name: 'Facilitator',
          component: Facilitator,
          meta: {requiresAuth: true, title: "服务商信息", activeTopMenu: 'Facilitator',activeSidebar:'FacilitatorList' }
        },
        {
          path: '/facilitator/add',
          name: 'FacilitatorAdd',
          component: FacilitatorAdd,
          meta: {requiresAuth: true, title: "添加服务商", activeTopMenu: 'Facilitator',activeSidebar:'FacilitatorList' }
        },
        {
          path: '/facilitator/edit/:id',
          name: 'FacilitatorEdit',
          component: FacilitatorEdit,
          meta: {requiresAuth: true, title: "编辑服务商", activeTopMenu: 'Facilitator',activeSidebar:'FacilitatorList' }
        },
        {
          path: '/facilitator/detail/:id',
          name: 'FacilitatorDetail',
          component: FacilitatorDetail,
          meta: {requiresAuth: true, title: "服务商详情", activeTopMenu: 'Facilitator',activeSidebar:'FacilitatorList' }
        }
      ]
    },
    {
      path: '/business',
      name:'business',
      component: Business,
      meta: {requiresAuth: true, title: "入驻商家" },
      children: [
        {
          path: '/business',
          name: 'businesslist',
          component: BusinessList,
          meta: {requiresAuth: true, title: "入驻商家", activeTopMenu: 'businesslist',activeSidebar:'businesslist' }
        },
        {
          path: '/business/detail/:id',
          name: 'businessdetail',
          component: BusinessDetail,
          meta: {requiresAuth: true, title: "商家详情", activeTopMenu: 'businesslist',activeSidebar:'businesslist' }
        },
        {
          path: '/business/add',
          name: 'businessadd',
          component: BusinessAdd,
          meta: {requiresAuth: true, title: "商家详情", activeTopMenu: 'businesslist',activeSidebar:'businesslist' }
        },
        {
          path: '/business/edit/:id',
          name: 'BusinessEdit',
          component: BusinessEdit,
          meta: {requiresAuth: true, title: "商家详情", activeTopMenu: 'businesslist',activeSidebar:'businesslist' }
        }
      ]
    },
    {
      path: '/moker',
      name:'moker',
      component: Moker,
      meta: {requiresAuth: true, title: "魔客列表" },
      children: [
        {
          path: '/moker',
          name: 'MokerList',
          component: MokerList,
          meta: {requiresAuth: true, title: "魔客列表", activeTopMenu: 'MokerList',activeSidebar:'MokerList' }
        },
        {
          path: '/moker/detail/:id',
          name: 'moker_detail',
          component: MokerDetail,
          meta: {requiresAuth: true, title: "魔客详情", activeTopMenu: 'MokerList',activeSidebar:'MokerList' }
        },
        {
          path: '/moker/setting',
          name: 'moker_setting',
          component: MokerSetting,
          meta: {requiresAuth: true, title: "魔客设置", activeTopMenu: 'MokerList',activeSidebar:'MokerList' }
        }
      ]
    },
    {
      path: '/setting',
      name:'setting',
      component: Setting,
      meta: {requiresAuth: true, title: "设置" },
      children: [
        {
          path: '/setting',
          name: 'setting_account',
          component: AccountSetting,
          meta: {requiresAuth: true, title: "账号设置", activeTopMenu: 'setting_account',activeSidebar:'setting_account' }
        },
        {
          path: '/setting/password',
          name: 'setting_password',
          component: PasswordSetting,
          meta: {requiresAuth: true, title: "提现信息", activeTopMenu: 'setting_account',activeSidebar:'setting_password' }
        },
        {
          path: '/setting/withdraw',
          name: 'setting_withdraw',
          component: WithdrawSetting,
          meta: {requiresAuth: true, title: "修改密码", activeTopMenu: 'setting_account',activeSidebar:'setting_withdraw' }
        }
      ]
    },
    {
      path: '/trade',
      name:'Trade',
      component: Trade,
      meta: {requiresAuth: true, title: "佣金明细" },
      children: [
        {
          path: '/trade',
          name: 'TradeList',
          component: TradeList,
          meta: {requiresAuth: true, title: "佣金明细", activeTopMenu: 'TradeList',activeSidebar:'TradeList' }
        }
      ]
    }
  ],
  scrollBehavior: function (to, from, savedPosition) {
    return { x: 0, y: 0 }
  }
});

router.beforeEach((to, from, next) => {
    iView.LoadingBar.start();
    next();
});

router.afterEach((to, from, next) => {
    iView.LoadingBar.finish();
});
router.beforeEach((to, from, next) => {
  console.log()
  if(to.meta.requiresAuth) {
    if(Store.state.authUser.authenticated || jwtToken.getToken())
      return next();
    else
      return next({path: '/login'});
  }
  if(to.meta.requiresGuest) {
    if(Store.state.authUser.authenticated || jwtToken.getToken())
      return next({path: '/'});
    else
      return next();
  }
  next();
});

export default router;