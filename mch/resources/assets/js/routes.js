import Vue from 'vue';
import VueRouter from 'vue-router';
import iView from 'iview';
import jwtToken from './helpers/jwt-token';
import Store from './store'

Vue.use(VueRouter);

import Main from './components/layouts/Main.vue';
import OrderLayout from './components/order/layouts/Sidebar.vue';
import MemberLayout from './components/member/layouts/Sidebar.vue';
import TimelineLayout from './components/timeline/layouts/Sidebar.vue';
import SettingLayout from './components/setting/layouts/Sidebar.vue';
import TradeLayout from './components/trade/layouts/Sidebar.vue';

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    linkActiveClass: 'active',
    routes: [
        {
            path: '/login',
            name: '登录',
            component: require('./components/auth/Login.vue'),
            meta: { requiresGuest: true }
        },
        {
            path: '/demo',
            name: '控件例子',
            component: require('./components/demo/Main.vue'),
            meta: {}
        },
        {
            path: '/',
            component: Main,
            children: [
                {
                    path: '/',
                    redirect: '/member',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'member',
                    redirect: 'member/list',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'member',
                    name: '会员',
                    component: MemberLayout,
                    children:[
                        /*                    {
                         path: 'dashboard',
                         name: '会员概况',
                         component: require('./views/member/Dashboard.vue'),
                         },*/
                        {
                            path: 'list',
                            name: '会员列表',
                            component: require('./components/member/List.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'member',activeSidebar:'member' }
                        },
                        {
                            path: 'credit/:id',
                            name: '魔豆记录',
                            component: require('./components/member/Credit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'member',activeSidebar:'member' }
                        },
                        {
                            path: 'balance/:id',
                            name: '余额记录',
                            component: require('./components/member/Balance.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'member',activeSidebar:'member' }
                        },
                        {
                            path: 'setting',
                            name: '会员设置',
                            component: require('./components/member/Setting.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'member',activeSidebar:'member/setting' }
                        }
                    ]
                },
                {
                    path: 'order',
                    redirect: 'order/list',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'order',
                    name: '订单',
                    component: OrderLayout,
                    children:[
                        {
                            path: 'list',
                            name: '订单列表',
                            component: require('./components/order/List.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'order' }
                        },
                        {
                            path: 'detail/:id',
                            name: '订单详情',
                            component: require('./components/order/Detail.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'order' }
                        }
                    ]
                },
                {
                    path: 'timeline',
                    redirect: 'timeline/list',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'timeline',
                    name: '商圈',
                    component: TimelineLayout,
                    children:[
                        {
                            path: 'list',
                            name: '商圈列表',
                            component: require('./components/timeline/List.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'timeline' }
                        },
                        {
                            path: 'add',
                            name: '发布商圈',
                            component: require('./components/timeline/Add.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'timeline' }
                        },
                        {
                            path: 'edit/:id',
                            name: '商圈编辑',
                            component: require('./components/timeline/Edit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'timeline' }
                        }
                    ]
                },
                {
                    path: 'trade',
                    redirect: 'trade/dashboard',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'trade',
                    name: '交易',
                    component: TradeLayout,
                    children:[
                        {
                            path: 'dashboard',
                            name: '交易概况',
                            component: require('./components/trade/Dashboard.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade', activeSidebar: 'trade/dashboard'}
                        },
                        {
                            path: 'list',
                            name: '收支明细',
                            component: require('./components/trade/List.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/list'}
                        },
                        {
                            path: 'withdraw',
                            name: '提醒申请',
                            component: require('./components/trade/Withdraw.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/withdraw'}
                        },
                        {
                            path: 'bankcard',
                            name: '银行卡管理',
                            component: require('./components/trade/Bankcard.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/bankcard'}
                        },
                        {
                            path: 'bankcard/add',
                            name: '添加银行卡',
                            component: require('./components/trade/BankcardAdd.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/bankcard'}
                        },
                        {
                            path: 'bankcard/edit/:id',
                            name: '编辑银行卡',
                            component: require('./components/trade/BankcardEdit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/bankcard'}
                        },
                        {
                            path: 'setting',
                            name: '交易设置',
                            component: require('./components/trade/Setting.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/setting'}
                        },
                        {
                            path: 'setting/view',
                            name: '设置查看',
                            component: require('./components/trade/SettingView.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/setting'}
                        },
                        {
                            path: 'setting/base/edit',
                            name: '企业基本信息编辑',
                            component: require('./components/trade/BaseEdit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/setting'}
                        },
                        {
                            path: 'setting/bank/edit',
                            name: '企业账户信息编辑',
                            component: require('./components/trade/BankEdit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/setting'}
                        },
                        {
                            path: 'setting/photo/edit',
                            name: '企业证件照片编辑',
                            component: require('./components/trade/PhotoEdit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/setting'}
                        },
/*                        {
                            path: 'setting/bankcard/edit',
                            name: '个体工商户绑卡信息编辑',
                            component: require('./components/trade/PersonBankCardEdit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'trade' , activeSidebar: 'trade/setting'}
                        }*/
                    ]
                },
                {
                    path: 'setting',
                    redirect: 'setting/shop',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'setting',
                    name: '设置',
                    component: SettingLayout,
                    children:[
                        {
                            path: 'shop',
                            name: '商户设置',
                            component: require('./components/setting/Shop.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'setting', activeSidebar: 'setting/shop'}
                        },
                        {
                            path: 'credit',
                            name: '魔豆设置',
                            component: require('./components/setting/Credit.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'setting' , activeSidebar: 'setting/credit'}
                        },
                        {
                            path: 'password',
                            name: '修改密码',
                            component: require('./components/setting/Password.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'setting' , activeSidebar: 'setting/password'}
                        },
                        {
                            path: 'qrcode',
                            name: '二维码',
                            component: require('./components/setting/Qrcode.vue'),
                            meta: { requiresAuth: true, activeTopMenu: 'setting' , activeSidebar: 'setting/qrcode'}
                        }
                    ]
                },
            ]
        }
    ]
});

router.beforeEach((to, from, next) => {
    iView.LoadingBar.start();
    next();
});

router.afterEach((to, from, next) => {
    iView.LoadingBar.finish();
});
router.beforeEach((to, from, next) => {
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