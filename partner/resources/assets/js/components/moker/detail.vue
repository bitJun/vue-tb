<template>
  <div class="moker_detail">
    <div class="base_info">
      <h4 class="title">
        <Breadcrumb>
          <BreadcrumbItem to="/moker">魔客列表</BreadcrumbItem>
          <BreadcrumbItem>{{moker.name}}({{moker.mobile}})</BreadcrumbItem>
        </Breadcrumb>
      </h4>
      <Row class="info_detail">
        <Col span="4">
          <img v-bind:src="moker.avatar || default_avatar" class="moker_logo pull-left">
        </Col>
        <Col span="10">
          <p>魔客等级：{{moker.level}}</p>
          <p>所在地区：{{moker.province}}{{moker.city}}{{moker.district}}</p>
          <p>加入时间：{{moker.created_at}}</p>
          <p>手机号码：{{moker.mobile}}</p>
        </Col>
        <Col span="10">
          <p>剩余商家入驻数：{{moker.shop_num}}</p>
          <p>邀请人：{{moker.invitation}}</p>
          <p>性别：{{moker.gender}}</p>
          <!-- <p>出生日期：</p> -->
        </Col>
      </Row>
      <!-- <Row class="balance">
        <Col span="6">
          <p class="text-center">今日收益（元）</p>
          <h3 class="text-center">12293.00</h3>
        </Col>
        <Col span="6">
          <p class="text-center">累计收益（元）</p>
          <h3 class="text-center">32313123.00</h3>
        </Col>
        <Col span="6">
          <p class="text-center">入驻商家</p>
          <h3 class="text-center">8</h3>
        </Col>
        <Col span="6">
          <p class="text-center">邀约魔客</p>
          <h3 class="text-center">3</h3>
        </Col>
      </Row> -->
    </div>
    <!-- <div class="balance_List">
      <h4 class="title">魔客收支明细</h4>
      <div class="list">
        <Tabs value="name1">
          <TabPane label="所有" name="all"><Balance></Balance></TabPane>
          <TabPane label="交易佣金" name="Check"><Balance></Balance></TabPane>
          <TabPane label="魔客奖金" name="Recharge"><Balance></Balance></TabPane>
          <TabPane label="提现" name="Withdrawals"><Balance></Balance></TabPane>
        </Tabs>
      </div>
    </div> -->
  </div>
</template>
<script>
import Balance from './balance.vue'
export default {
  name: 'moker_detail',
  data () {
    return {
      moker: {},
      default_avatar: 'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=179143397,810986665&fm=27&gp=0.jpg'
    }
  },
  components: {
    Balance
  },
  created () {
    let id = this.$route.params.id
    this.$store.dispatch('mokerDetailRequest', {id: id})
      .then((res) => {
        this.moker = res
      })
      .catch((error) => {
        this.$Message.error(error);
      });
  },
  methods: {
  }
}
</script>
