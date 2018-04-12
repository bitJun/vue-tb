<template>
  <div class="setting_view">
    <div class="layout_view">
      <Menu class="inner-menu" theme="light" style="width:200px;padding-top:65px" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
        <Menu-group title="佣金管理">
          <Menu-item name="TradeList">
            <Icon type="ios-list"></Icon>
            佣金明细
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
export default {
  name: 'setting',
  methods: {
    routerLink(name) {
      this.$router.push({ name: name });
    }
  }
}
</script>
