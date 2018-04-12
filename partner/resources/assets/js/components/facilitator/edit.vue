<template>
  <div class="add_facilitator">
    <h4 class="title">编辑服务商</h4>
    <Form  ref="facilitatorDate" :model="facilitatorDate" :rules="ruleValidate"  class="add_form" :label-width="100">
      <FormItem label="公司名称" prop="company_name">
        <Input v-model="facilitatorDate.company_name" placeholder="请填写公司名称" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="公司地址" prop="address">
        <Input v-model="facilitatorDate.address" placeholder="请填写联系人" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="联系人" prop="account">
        <Input v-model="facilitatorDate.name" placeholder="请填写联系人" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="联系电话" prop="mobile">
        <Input v-model="facilitatorDate.mobile" placeholder="请填写联系电话" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="服务城市" class="region-picker">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle">
            <span v-text="labelData"></span> <span class="caret"></span>
        </button>
      </FormItem>
      <FormItem label="过期时间" prop="endtime">
        <DatePicker type="date" v-model="facilitatorDate.endtime" format="yyyy年MM月dd日" placeholder="请选择结束时间" style="width: 400px"></DatePicker>
      </FormItem>
      <FormItem label="业务经理" prop="manager">
        <Input v-model="facilitatorDate.manager" placeholder="请填写业务经理" style="width: 400px"></Input>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="onSubmit">立即添加</Button>
      </FormItem>
    </Form>
  </div>
</template>
<script>
import RegionPicker from '../common/RegionPicker.vue';
export default {
  name: 'EditBusiness',
  data () {
    return {
      value:[],
      display: true,
      labelData: '',
      regionInfo: {
        province_id: 0,
        province_name: '',
        city_id: 0,
        city_name: ''
      },
      facilitatorDate: {
        company_name: '',
        account: '',
        mobile: '',
        province_id:'',
        city_id: '',
        district_id:'',
        endtime: '',
        manager: ''
      },
      ruleValidate: {
        address: [
          { required: true, message: '公司地址不能为空', trigger: 'blur' }
        ],
        company_name: [
          { required: true, message: '公司名不能为空', trigger: 'blur' }
        ],
        account: [
          { required: true, message: '联系人不能为空', trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '手机号不能为空', trigger: 'blur' },
          { type: 'string', message: '请输入正确的手机号', pattern: /^1[345789]\d{9}$/}
        ]
      },
      cityList: [],
      selectedRegion: []
    }
  },
  components: {
    RegionPicker
  },
  created () {
  },
  mounted () {
    this.init();
  },
  methods: {
    init: function () {
      let id = this.$route.params.id;
      this.$store.dispatch('facilitatorShow', {id:id})
        .then((response) => {
          this.facilitatorDate = response.info;
          this.facilitatorDate.endtime = this.facilitatorDate.endtime * 1000;
          this.labelData = response.info.region;
        })
        .catch((error) => {
          this.$Message.error(error);
        });
    },
    onSubmit() {
      let endtime = this.facilitatorDate.endtime;
      if (typeof(endtime) === 'object') {
        endtime = Date.parse(endtime) / 1000;
      } else if (typeof(endtime) === 'number') {
        endtime = endtime / 1000;
      }
      let data = {
        id: this.$route.params.id,
        company_name: this.facilitatorDate.company_name,
        name: this.facilitatorDate.name,
        manager: this.facilitatorDate.manager,
        mobile: this.facilitatorDate.mobile,
        endtime: endtime
      }
      this.$store.dispatch('facilitatorEdit', data)
        .then((response) => {
            if(response.status==200)
            {
                this.$Message.success(response.data);
            }else{
                this.$Message.error(response.data);
            }
            this.init();
        })
        .catch((error) => {
          this.$Message.error(error);
        });
    }
  },
  // 监听条件变化
  watch: {
    'selectedRegion' (val, oldVal) {
      this.form.province_id = val[0];
      this.form.city_id = val[1];
      this.form.district_id = val[2];
    }
  }
}
</script>