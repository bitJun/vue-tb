<template>
  <div class="business_view">
    <div class="layout_view">
      <Menu class="inner-menu" theme="light" style="width:200px;padding-top:65px" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
        <Menu-group title="商家管理">
          <Menu-item name="businesslist">
            <Icon type="ios-list"></Icon>
            商家列表
          </Menu-item>
        </Menu-group>
      </Menu>
    </div>
    <div class="main-container">
      <div class="single-page">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>
<script>
import business from './business.vue'
export default {
  name: 'business',
  components: {
    business
  },
  methods: {
    routerLink(name) {
      this.$router.push({ name: name });
    }
  }
}
</script>
