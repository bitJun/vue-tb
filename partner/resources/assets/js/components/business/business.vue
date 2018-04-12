<template>
  <div class="business_view">
    <ul class="profit clearfix">
      <li>
        <h3>{{json.newadd}}</h3>
        <p>今日新增</p>
      </li>
      <li>
        <h3>{{json.settled}}</h3>
        <p>已入驻商家</p>
      </li>
      <li>
        <h3>{{json.Audit}}</h3>
        <p>待审核商家</p>
      </li>
      <li>
        <h3>{{json.profit}}</h3>
        <p>类及佣金</p>
      </li>
    </ul>
    <div class="list">
      <ul class="filiter clearfix">
        <li>待审核商家</li>
        <li>已入驻商家</li>
      </ul>
      <<router-link to="/business/add" class="add">添加商户</router-link>
      <table>
        <thead>
          <tr>
            <td v-bind:style="{width: '10%'}">序号</td>
            <td v-bind:style="{width: '10%'}">服务城市</td>
            <td v-bind:style="{width: '10%'}">公司名称</td>
            <td v-bind:style="{width: '10%'}">联系人</td>
            <td v-bind:style="{width: '10%'}">联系电话</td>
            <td v-bind:style="{width: '10%'}">开通时间</td>
            <td v-bind:style="{width: '10%'}">详情</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in data">
            <td v-bind:style="{width: '10%'}">{{item.id}}</td>
            <td v-bind:style="{width: '10%'}">{{item.city}}</td>
            <td v-bind:style="{width: '10%'}">{{item.company}}</td>
            <td v-bind:style="{width: '10%'}">{{item.connect}}</td>
            <td v-bind:style="{width: '10%'}">{{item.mobile}}</td>
            <td v-bind:style="{width: '10%'}">{{item.create_at | time}}</td>
            <td v-bind:style="{width: '10%'}">查看</td>
          </tr>
        </tbody>
      </table>
      <Pages></Pages>
    </div>
  </div>
</template>
<script>
import Pages from '../common/pages.vue'
export default {
  name: 'business',
  data () {
    return {
      json: {
        profit: '92988.27',
        newadd: '233',
        settled: '133134',
        Audit: '31'
      },
      data: []
    }
  },
  created () {
    for (let i = 0; i < 10; i++) {
      let data = {
        id: i + 1,
        city: '上海11',
        company: '杭州淘推科技有限公司',
        connect: 'XXX',
        mobile: '18812883212',
        create_at: new Date()
      }
      this.data.push(data)
    }
  },
  components: {
    'Pages': Pages
  }
}
</script>
