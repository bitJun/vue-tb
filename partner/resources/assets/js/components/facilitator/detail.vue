<template>
  <div class="facilitator_detail">
    <div class="baseInfo">
      <h4 class="title">{{json.area}}服务商</h4>
      <Row class="facilitatorInfo">
        <Col span="8">
          <p>服务地区：{{json.region}}</p>
        </Col>
        <Col span="8">
          <p>过期时间：{{json.expire_at}}</p>
        </Col>
        <Col span="8">
          <p>联系人：{{json.name}}</p>
        </Col>
        <Col span="8">
          <p>业务经理：{{json.manager}}</p>
        </Col>
        <Col span="8">
          <p>联系电话：{{json.mobile}}</p>
        </Col>
        <Col span="8">
          <p>平台账号：{{json.account}}</p>
        </Col>
      </Row>
    </div>
    <div class="underlist">
      <div class="list">
        <Card :bordered="true">
            <Input placeholder="公司名称" v-model="paramsData.company_name" style="width: 200px;margin-right: 10px"></Input>
            <Input placeholder="联系人" v-model="paramsData.name" style="width: 200px;margin-right: 10px"></Input>
            <Input placeholder="联系电话" v-model="paramsData.mobile" style="width: 200px;margin-right: 10px"></Input>
            <Button type="primary" @click="search()">筛选</Button>
        </Card>
            <Table border ref="selection" :columns="columns4" :data="shopdata"></Table>
        </div>
        <Pages :count="count" @transferpage="changePage"></Pages>
      </div>
    </div>
    <!-- <div class="tradeInfo">
      <Row class="profit">
        <Col span="8">
          <h3 class="text-center">{{statistics.income}}(元)</h3>
          <p class="text-center">累计收益</p>
        </Col>
        <Col span="8">
          <h3 class="text-center">{{statistics.shopCount}}</h3>
          <p class="text-center">辖区商家数</p>
        </Col>
        <Col span="8">
          <h3 class="text-center">{{statistics.mokerCount}}</h3>
          <p class="text-center">辖区墨客</p>
        </Col>
      </Row>
    </div> -->
  </div>
</template>
<script>
import {mapState} from 'vuex'
import Pages from '../common/pages.vue'
export default {
  name: 'FacilitatorDetail',
  data () {
    return {
      id:'',
      json: [],
      paramsData: {
        company_name: '',
        name: '',
        mobile: '',
        limit: 10,
        offset: 0,
        id: ''
      },
      columns4: [
        {
          title: '服务地区',
          key: 'city'
        },
        {
          title: '公司名称',
          key: 'company_name'
        },
        {
          title: '联系人',
          key: 'name'
        },
        {
          title: '联系电话',
          key: 'mobile'
        },
        {
          title: '加入时间',
          key: 'created_at'
        },
        {
          title: '过期时间',
          key: 'expire_at'
        },
        {
          title: '操作',
          key: 'action',
          width: 180,
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
              }, '查看下级'),
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
                    this.edit(id)
                  }
                }
              }, '编辑'),
              h('a', [
                //deleteButton(this, h, row, id)
              ])
            ]);
          }
        }
      ]
    }
  },
  created () {
    this.$nextTick(function () {
      let id = this.$route.params.id;
      this.id = id;
      this.init();
      this.getlist(id);
    })
  },
  components: {
    Pages
  },
  computed: {
    ...mapState({
      shopdata: state => state.facilitator.shopdata,
      count: state => state.facilitator.count
    })
  },
  methods: {
    init : function () {
      this.$store.dispatch('facilitatorShow', {id: this.id})
        .then((response) => {
          this.json = response.info;
        })
        .catch((error) => {
          this.$Message.error(error);
        });
    },
    changePage: function(offset){
      this.paramsData.offset = (offset-1)*10
      this.getlist()
    },
    search () {
      this.paramsData.offset = 0;
      this.$store.dispatch('facilitatorList', this.paramsData);
    },
    getlist (id) {
      this.paramsData.id = id;
      this.$store.dispatch('facilitatorList', this.paramsData)
    }
  }
}
</script>