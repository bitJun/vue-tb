<template>
  <div class="setting_account">
    <Form  ref="baseInfo" :model="baseInfo" :rules="ruleValidate"  class="add_form" :label-width="100">
      <h4 class="title">基本信息</h4>
      <FormItem label="公司名称" prop="company_name">
        <Input v-model="baseInfo.company_name" placeholder="请输入公司名称" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="公司地址">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" v-if="display">
            <span v-text="labelData"></span> <span class="caret"></span>
        </button>
        <RegionPicker v-model="selectedRegion" :regionsId="regionInfo" v-if="!display"></RegionPicker>
      </FormItem>
      <FormItem label="详细地址" prop="address">
        <Input v-model="baseInfo.address" placeholder="请输入详细地址" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="联系人姓名" prop="name">
        <Input v-model="baseInfo.name" placeholder="请输入联系人" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="联系人手机" prop="mobile">
        <Input v-model="baseInfo.mobile" placeholder="请输入联系人手机号" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="业务经理">
        <Input v-model="baseInfo.manager" placeholder="请输入联系人手机号" style="width: 400px" disabled></Input>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="change()">保存</Button>
      </FormItem>
    </Form>
  </div>
</template>
<script>
import RegionPicker from '../common/RegionPicker.vue';
export default {
  name: 'setting_account',
  data() {
    return {
      display: true,
      value: [],
      labelData: '请选择区域',
      regionInfo: {
        province_id: 0,
        province_name: '',
        city_id: 0,
        city_name: ''
      },
      selectedRegion: [],
      baseInfo: {},
      ruleValidate: {
        company_name: [
          { required: true, message: '公司名不能为空', trigger: 'blur' }
        ],
        connect: [
          { required: true, message: '联系人不能为空', trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '手机号不能为空', trigger: 'blur' },
          { type: 'string', message: '请输入正确的手机号', pattern: /^1[34578]\d{9}$/}
        ],
        city: [
          { required: true, message: '请选择城市', trigger: 'change' }
        ],
        account: [
          { required: true, message: '登录账号不能为空', trigger: 'change' }
        ],
        pwd: [
          { required: true, message: '密码不能为空', trigger: 'change' },
          { min: 6, max: 16, message: '密码8-16位', trigger: 'blur' },
          { type: 'string', message: '必须为数字值'}
        ]
      }
    }
  },
  components: {
    RegionPicker
  },
  created () {
    this.init();
  },
  methods: {
    init () {
      this.$store.dispatch('facilitatorInfo')
        .then((res) => {
          this.baseInfo = res.info;
          this.regionInfo.province_id = res.info.province_id;
          this.regionInfo.province_name = res.info.province_name;
          this.labelData = res.info.province_name;
          if(res.info.city_id!=0){
            this.regionInfo.city_id = res.info.city_id;
            this.regionInfo.city_name = res.info.city_name;
            this.labelData += res.info.city_name;
          }
          if (res.info.district_id!=0) {
            this.regionInfo.district_id = res.info.district_id;
            this.regionInfo.district_name = res.info.district_name;
            this.labelData += res.info.district_name;
          }
          this.display = !this.display;
        })
        .catch((error) => {
          this.$Message.error(error);
        });
    },
    change () {
      this.baseInfo.endtime = Date.parse(this.baseInfo.expire_at) / 1000;
      this.$store.dispatch('facilitatorEdit',this.baseInfo)
        .then((res) => {
            if(res.status==200)
            {
                this.$Message.success(res.data);
            }else{
                this.$Message.error(res.data);
            }
            this.init();
        })
        .catch((error) => {
          this.$Message.error(error);
        })
    }
  },
  watch: {
    'selectedRegion' (val, oldVal) {
      this.baseInfo.province_id = val[0];
      this.baseInfo.city_id = val[1];
      this.baseInfo.district_id = val[2];
    }
  }
}
</script>
