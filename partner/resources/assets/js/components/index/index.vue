<template>
  <div class="index_view">
    <div class="layout_view">
      <Menu class="inner-menu" theme="light" style="width:200px;padding-top:65px" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
        <Menu-group title="首页">
          <Menu-item name="index">
            <Icon type="stats-bars"></Icon>
            概况
          </Menu-item>
        </Menu-group>
      </Menu>
    </div>
    <div class="main-container">
      <div class="single-page">
        <div class="section">
          <h4 class="title">佣金</h4>
          <div class="Overview">
            <Row>
              <Col span="11">
                <h4 class="text-center">余额</h4>
                <p class="price text-center">￥{{jsondata.balance}}</p>
              </Col>
              <Col span="11" offset="2">
                <h4 class="text-center">我的收入</h4>
                <p class="price text-center">￥{{jsondata.Profit}}</p>
              </Col>
            </Row>
          </div>
        </div>
        <div class="section">
          <h4 class="title">商家</h4>
          <Row class="businessInfo">
            <Col span="12">
              <p>今日新增</p>
              <h4 class="clr-payout">{{jsondata.add_shop}}</h4>
            </Col>
            <Col span="12">
              <p>已入驻商家</p>
              <h4 class="clr-payout">{{jsondata.accumulate_shop}}</h4>
            </Col>
          </Row>
        </div>
        <div class="section">
          <h4 class="title">魔客</h4>
          <Row class="mokerInfo">
            <Col span="8">
              <p class="text-center">今日新增</p>
              <h3 class="text-center clr-payout">{{jsondata.add_moker}}</h3>
            </Col>
            <Col span="8">
              <p class="text-center">累计魔客</p>
              <h3 class="text-center clr-payout">{{jsondata.accumulate_moker}}</h3>
            </Col>
            <Col span="8">
              <p class="text-center">累计邀约商家</p>
              <h3 class="text-center clr-payout">{{jsondata.moker_shop}}</h3>
            </Col>
          </Row>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {mapState} from 'vuex'
export default {
  name: 'index',
  data () {
    return {
    }
  },
  created () {
    this.init();
    console.log('jsondata', this.jsondata)
  },
  computed: {
    ...mapState({
      jsondata: state => state.index.jsondata
    })
  },
  methods: {
    init : function () {
      this.$store.dispatch('indexRequest', this.paramsData);
    },
    routerLink(name) {
        this.$router.push({ name: name });
    }
  }

}
</script>