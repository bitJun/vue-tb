<template>
  <div class="tradeList_view">
    <h4 class="title">佣金明细</h4>
    <div class="list">
      <Card :bordered="true">
        <Input v-model="paramsData.order_sn" placeholder="订单编号" style="width: 200px;margin-right: 10px"></Input>
        <DatePicker v-model="timerange" type="daterange" :options="options2" placement="bottom-end" placeholder="时间" style="width: 200px;margin-right: 10px"></DatePicker>
        <Select v-model="paramsData.type" style="width:200px">
          <Option v-for="item in type" :value="item.value" :key="item.value">{{ item.label }}</Option>
        </Select>
        <Button type="primary" @click="search()">筛选</Button>
      </Card>
      <Table border ref="selection" :columns="columns4" :data="commissionlist"></Table>
      <Pages :count="count" @transferpage="changePage" style="margin-top:20px"></Pages>
    </div>
  </div>
</template>
<script>
import Pages from '../common/pages.vue';
import {mapState} from 'vuex'
export default {
  name: 'tradeList',
  data () {
    return {
      timerange: '',
      options2: {
        disabledDate (date) {
          return date && date.valueOf() > Date.now();
        },
        shortcuts: [
          {
            text: '今天',
            value () {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime());
              return [start, end];
            }
          },
          {
            text: '昨天',
            value () {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24);
              return [start, end];
            }
          },
          {
            text: '一周前',
            value () {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              return [start, end];
            }
          },
          {
            text: '一个月前',
            value () {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              return [start, end];
            }
          }
        ]
      },
      columns4: [
        {
          title: '时间',
          key: 'date'
        },
        {
          title: '类型|单号',
          key: 'type',
          render: (h, params) => {
            const row = params.row;
            const type = row.type;
            const order_sn = row.order_sn;
            let text = '';
            const classname = 'order';
            text = type + '|' + order_sn;
            return h('label', {
              'class':'clr-bule'
            }, text);
          }
        },
        {
          title: '订单金额',
          key: 'order_amount',
          render: (h, params) => {
            const row = params.row;
            const amount = '￥' + row.order_amount;
            return h('label',{
              'class': 'clr-payout'
            },amount)
          }
        },
        {
          title: '佣金',
          key: 'comm',
          render: (h, params) => {
            const row = params.row;
            const comm = '￥' + row.comm;
            return h('label',{
              'class': 'clr-payout'
            },comm)
          }
        }
      ],
      json: [],
      paramsData: {
        limit:10,
        offset:0,
        type:'',
        order_sn:'',
        start:'',
        end:''
      },
      type: [
        {
          value: '',
          label: '请选择'
        },
        {
          value: 0,
          label: '交易佣金'
        },
        {
          value: 1,
          label: '邀请魔客佣金'
        }
      ]
    }
  },
  components: {
    Pages
  },
  created () {
    this.init();
    this.$store.dispatch('commissionRequest', this.paramsData);
  },
  computed: {
    ...mapState({
      commissionlist: state => state.trade.commissionlist,
      count: state => state.trade.count
    })
  },
  methods: {
    init () {
      for (let i = 0; i < 10; i++) {
        let data = {
          id: i + 1,
          orderId: 'E201711020805519850551',
          created_at: '2017-11-17 18:21:45',
          price: 1000,
          type: 1
        }
        if (i % 3 === 2) {
          data.type = 0
        }
        this.json.push(data)
      }
    },
    search () {
      let date_start = Date.parse(new Date(this.timerange[0]))
      let date_end = Date.parse(new Date(this.timerange[1]))
      this.paramsData.start = date_start;
      this.paramsData.end = date_end;
      this.$store.dispatch('commissionRequest', this.paramsData);
    },
    changePage: function(offset){
      this.paramsData.offset =  (offset-1)*10;
      this.$store.dispatch('commissionRequest', this.paramsData);
    }
  }
}
</script>

