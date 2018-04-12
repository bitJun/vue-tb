<template>
  <div class="Balance_view">
    <Table border ref="selection" :columns="columns4" :data="json" class="tradeList"></Table>
    <Pages></Pages>
  </div>
</template>
<script>
import Pages from '../common/pages.vue'
export default {
  props: {
    data: {
      type: String
    }
  },
  name: 'balance',
  data () {
    return {
      columns4: [
        {
            title: '序号',
            key: 'id'
        },
        {
            title: '交易类型',
            key: 'type'
        },
        {
            title: '交易时间',
            key: 'time'
        },
        {
            title: '交易金额',
            key: 'price'
        }
      ],
      json: []
    }
  },
  components: {
    Pages
  },
  created () {
    this.init()
  },
  methods: {
    init () {
      for (let i = 0; i < 10; i++) {
        let data = {
          id: i + 1,
          type: 0,
          time: new Date(),
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
