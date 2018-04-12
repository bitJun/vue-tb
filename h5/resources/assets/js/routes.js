import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    linkActiveClass: 'active',
    routes: [
        {
            path: '/wxpay/:token',
            name: 'wxpay',
            component: require('./components/cash/Wxpay.vue'),
            meta: { title: "向商家付款" }
        },
        {
            path: '/alipay/:token',
            name: 'alipay',
            component: require('./components/cash/Alipay.vue'),
            meta: { title: "向商家付款" }
        },
        {
            path: '/paid/:sid/:osn',
            name: 'paid',
            component: require('./components/cash/Paid.vue'),
            meta: { title: "付款成功"}
        }
    ]
});

router.beforeEach((to, from, next) => {
    if(to.meta.title) {
        document.title = to.meta.title;
    }
    next();
});

router.afterEach((to, from, next) => {

});

export default router;