<template>
  <div class="business_detail">
    <div class="base_info">
      <h4 class="title clearfix">
        外婆家
        <ul class="btns">
          <li>
            <a @click="edit()">编辑</a>
          </li>
        </ul>
      </h4>
      <Row class="tradeInfo">
        <Col span="6">
          <router-link to="">
            <p class="text-center">今日交易额（万元）</p>
            <h4 class="text-center">166</h4>
          </router-link>
        </Col>
        <Col span="6">
          <router-link to="">
            <p class="text-center">累计交易额（万元）</p>
            <h4 class="text-center">266</h4>
          </router-link>
        </Col>
        <Col span="6">
          <router-link to="">
            <p class="text-center">未结算金额（万元）</p>
            <h4 class="text-center">{{json.expect}}</h4>
          </router-link>
        </Col>
        <Col span="6">
          <router-link to="">
            <p class="text-center">累计交易佣金（万元）</p>
            <h4 class="text-center">268</h4>
          </router-link>
        </Col>
      </Row>
      <Row class="industry">
        <Col span="10" offset="2">
          <p>所在地区：{{json.area}}</p>
        </Col>
        <Col span="12">
          <p>入驻时间：{{json.created_at}}</p>
        </Col>
        <Col span="10" offset="2">
          <p>联系人：{{json.contact}}</p>
        </Col>
        <Col span="12" offset="2">
          <p>联系电话：{{json.tel}}</p>
        </Col>
      </Row>
      <div class="introduce clearfix">
        <img v-bind:src="json.logo" class="shop_logo pull-left">
        <div class="introduceInfo">
          <p>所属行业：</p>
          <p>地址：{{json.address}}</p>
          <p>介绍：{{json.intro}}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Balance from './balance.vue'
export default {
  name: 'businessdetail',
  data () {
    return {
      json: {},
      activeName: 'all',
      balance: 'all'
    }
  },
  components: {
    Balance
  },
  created () {
    this.init()
  },
  methods: {
    init () {
      let id = this.$route.params.id;
      this.$store.dispatch('getShopDetail', {id: id})
        .then((res) => {
          this.json = res
        })
        .catch((error) => {
          this.$Message.error(error);
        });
    },
    handleClick(tab, event) {
      this.balance = tab.name;
    },
    edit () {
      let id = this.$route.params.id;
      this.$router.push({name: 'BusinessEdit', params: { id: id }})
    }
  }
}
</script>
