<template>
  <div class="moker_list">
    <div class="moker_list">
       <Row style="margin-bottom:20px">
          <Input placeholder="昵称" style="width: 200px;margin-right:20px" v-model="paramsData.name" />
          <Input placeholder="手机号" style="width: 200px;margin-right:20px" v-model="paramsData.mobile" />
          <Button type="primary" icon="ios-search" @click="search()">查询</Button>
        </Row>
      <Table border ref="selection" :columns="columns4" :data="mokerdata"></Table>
      <Pages :count="count" @transferpage="changePage"></Pages>
    </div>
  </div>
</template>
<script>
import Pages from '../common/pages.vue';
import {mapState} from 'vuex'
export default {
  name: 'MokerList',
  data () {
    return {
      columns4: [
        {
            title: '所在地区',
            key: 'addres'
        },
        {
            title: '魔客等级',
            key: 'level'
        },
        {
            title: '昵称',
            key: 'name'
        },
        {
            title: '手机号',
            key: 'mobile'
        },
        {
            title: '入驻商家数',
            key: 'shop'
        },
        {
            title: '邀约魔客',
            key: 'invitation'
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
              }, '查看')
            ]);
          }
        }
      ],
      json: [],
      value: '',
      model1: '',
      keyword: '',
      paramsData: {
        limit: 10,
        offset: 0,
        name: '',
        mobile: ''
      }
    }
  },
  components: {
    Pages
  },
  created () {
    this.init()
  },
	mounted () {
		let $self = this;
		document.onkeydown = function (e) {
      		if (e && e.keyCode === 13) {
        		$self.search()
      		}
    	}
	},
  computed: {
    ...mapState({
      mokerdata: state => state.moker.mokerdata,
      count: state => state.moker.count
    })
  },
  methods: {
    init () {
      this.$store.dispatch('mokerRequest', this.paramsData);
    },
    show (id) {
      this.$router.push({name: 'moker_detail', params: { id: id }})
    },
    toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTable.toggleRowSelection(row);
        });
      } else {
        this.$refs.multipleTable.clearSelection();
      }
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    changePage: function(offset){
      this.paramsData.offset =  (offset-1)*10;
      this.$store.dispatch('mokerRequest', this.paramsData);
    },
    search () {
      this.$store.dispatch('mokerRequest', this.paramsData);
    }
  }
}
</script>
