<template>
  <div class="moker_view">
    <div class="layout_view">
      <Menu class="inner-menu" theme="light" style="width:200px;padding-top:65px" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
        <Menu-group title="魔客管理">
          <Menu-item name="MokerList">
            <Icon type="ios-list"></Icon>
            魔客列表
          </Menu-item>
          <!-- <Menu-item name="moker_setting">
            <Icon type="ios-list"></Icon>
            魔客设置
          </Menu-item> -->
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
