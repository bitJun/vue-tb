<template>
  <div class="facilitator_view">
    <div class="layout_view">
      <Menu class="inner-menu" theme="light" style="width:200px;padding-top:65px" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
        <Menu-group title="服务商管理">
          <!-- <Menu-item name="Facilitator">
            <Icon type="ios-list"></Icon>
            服务商信息
          </Menu-item> -->
          <Menu-item name="FacilitatorList">
            <Icon type="ios-list"></Icon>
            下级服务商
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
  name: 'facilitator',
  methods: {
    routerLink(name) {
      this.$router.push({ name: name });
    }
  }
}
</script>
