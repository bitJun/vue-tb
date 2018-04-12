<template>
  <div class="setting_view">
    <div class="layout_view">
      <Menu class="inner-menu" theme="light" style="width:200px;padding-top:65px" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
        <Menu-group title="设置管理">
          <Menu-item name="setting_account">
            <Icon type="ios-gear"></Icon>
            账号信息
          </Menu-item>
          <Menu-item name="setting_withdraw">
            <Icon type="ios-gear"></Icon>
            提现信息
          </Menu-item>
          <Menu-item name="setting_password">
            <Icon type="ios-gear"></Icon>
            修改密码
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
