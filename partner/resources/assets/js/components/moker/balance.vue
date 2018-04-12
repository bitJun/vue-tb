<template>
  <div class="moker_balance">
    <Table border ref="selection" :columns="columns4" :data="json" style="margin-bottom: 20px;"></Table>
    <Pages></Pages>
  </div>
</template>
<script>
import Pages from '../common/pages.vue';
export default {
  name: 'moker_balance',
  props: {
    data: {
      type: String
    }
  },
  data () {
    return {
       columns4: [
        {
            title: '序号',
            key: 'id'
        },
        {
            title: '来源',
            key: 'source'
        },
        {
            title: '交易类型',
            key: 'type',
            render: (h, params) => {
              const row = params.row;
              const type = row.type;
              const text = row.type === 0 ? '交易佣金' : row.type === 1 ? '魔客奖金' : row.type === 2 ? '提现' : 'Success';
              return h('Tag', text);
            }
        },
        {
            title: '交易时间',
            key: 'time'
        },
        {
            title: '交易金额',
            key: 'price',
            render: (h, params) => {
              const row = params.row;
              const price = row.price;
              let color = '';
              const text = '￥' + price;;
              if (Number(price)<0) {
                color = 'green';
              }
              else if (Number(price)>0) {
                color = 'red';
              }
              return h('Tag', {
                props: {
                  color: color
                }
              }, text);
            }
        }
      ],
      json: []
    }
  },
  created () {
    this.init()
  },
  components: {
    Pages
  },
  methods: {
    init () {
      for (let i = 0; i < 10; i++) {
        let data = {
          id: i + 1,
          type: 0,
          time: '2017-10-12',
          source: '春暖花开自助餐厅',
          price: 980
        }
        if (i % 3 === 1) {
          data.type = 1
          data.price = -1 * Number(data.price)
        }
        if (i % 3 === 2) {
          data.type = 2
        }
        this.json.push(data)
      }
    }
  },
  watch: {
    'data' (val, oldVal) {
      console.log('val',val);
    }
  }
}
</script>
