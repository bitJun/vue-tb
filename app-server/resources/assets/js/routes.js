import Vue from 'vue';
import VueRouter from 'vue-router';
import Invitation from './components/invitation/invitation.vue'
import Agreement from './components/agreement/agreement.vue'
import Download from './components/download/download.vue'
Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  linkActiveClass: 'active',
  routes: [
    {
      path: '/invitation/:id',
      name: 'invitation',
      component: Invitation,
      meta: { title: "魔客分享"}
    },
    {
      path: '/agreement',
      name: 'agreement',
      component: Agreement,
      meta: { title: "魔客协议"}
    },
    {
      path: '/download',
      name: 'download',
      component: Download,
      meta: { title: "魔客APP下载"}
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