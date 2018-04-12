<template>
  <div class="business_list">
    <h4 class="title">
      <Button type="primary" class="add" style="margin-left:10px">
        <router-link to="/business/add" style="color:#ffffff">添加商家</router-link>  
      </Button>
    </h4>
    <div class="list">
      <Row style="margin-bottom:20px">
        <Input placeholder="商家名" style="width: 200px;margin-right:20px" v-model="params.name" />
        <Input placeholder="联系人" style="width: 200px;margin-right:20px" v-model="params.contact" />
        <Input placeholder="联系电话" style="width: 200px;margin-right:20px" v-model="params.tel" />
        <Button type="primary" icon="ios-search" @click="search()">查询</Button>
      </Row>
      <Table border ref="selection" :columns="columns4" :data="shopdata"></Table>
    </div>
    <Pages :count="count" @transferpage="changePage" style="margin-top:20px"></Pages>
  </div>
</template>
<script>
let $self = ''
import Pages from '../common/pages.vue'
import {mapState} from 'vuex'
export default {
  name: 'businesslist',
  data () {
    return {
      columns4: [
        {
          title: '所在地区',
          key: 'city_str',
          render: (h, params) => {
            const row = params.row;
            const province_str = row.province_str;
            const city_str = row.city_str;
            const district_str = row.district_str;
            const text = province_str + city_str + district_str;
            return h('div', text);
          }
        },
        {
          title: '商家名称',
          key: 'name'
        },
        {
          title: '联系人',
          key: 'contact'
        },
        {
          title: '联系电话',
          key: 'tel'
        },
        {
          title: '加入时间',
          key: 'created_at'
        },
        {
          title: '操作',
          key: 'action',
          width: 150,
          align: 'center',
          render: (h, params) => {
            const row = params.row;
            const id = row.id;
            return h('div', [
              h('a', {
                props: {
                  type: 'primary',
                  size: 'small'
                },
                style: {
                  marginRight: '5px'
                },
                on: {
                  click: () => {
                    this.show(id)
                  }
                }
              }, '查看'),
              h('a', {
                props: {
                  type: 'primary',
                  size: 'small'
                },
                on: {
                  click: () => {
                    this.edit(id)
                  }
                }
              }, '编辑')
            ]);
          }
        }
      ],
      json: [],
      params: {
        limit: 10,
        offset: 0,
        name: '',
        contact: '',
        tel: ''
      }
    }
  },
  created () {
    this.init()
  },
  components: {
    Pages
  },
  computed: {
    ...mapState({
      shopdata: state => state.shop.shopdata,
      count: state => state.shop.count
    })
  },
  methods: {
    init () {
      this.$store.dispatch('shopRequest', this.params);
    },
    show (id) {
      this.$router.push({name: 'businessdetail', params: { id: id }})
    },
    edit (id) {
      this.$router.push({name: 'BusinessEdit', params: { id: id }})
    },
    handleSelectAll (status) {
      this.$refs.selection.selectAll(status);
    },
    changePage: function(offset){
      this.params.offset = (offset-1)*10
      this.init()
    },
    search () {
      this.$store.dispatch('shopRequest', this.params);
    }
  }
}
</script>
