<template>
  <div class="add_facilitator">
    <h4 class="title">添加服务商</h4>
    <Form  ref="facilitatorDate" :model="facilitatorDate" :rules="ruleValidate"  class="add_form" :label-width="100">
      <FormItem label="公司名称" prop="company">
        <Input v-model="facilitatorDate.company" placeholder="请填写公司名称" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="联系人" prop="account">
        <Input v-model="facilitatorDate.account" placeholder="请填写联系人" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="联系电话" prop="mobile">
        <Input v-model="facilitatorDate.mobile" placeholder="请填写联系电话" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="服务城市" class="region-picker">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" v-if="display">
            <span v-text="labelData"></span> <span class="caret"></span>
        </button>
        <RegionPicker v-model="selectedRegion" :regionsId="regionInfo" v-if="!display"></RegionPicker>
        <!-- <Cascader :data="cityList" v-model="value" size="large" style="width: 400px;"></Cascader> -->
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
  name: 'AddBusiness',
  data () {
    return {
      value:[],
      display: true,
      labelData: '',
      regionInfo: {
        province_id: 0,
        province_name: '',
        city_id: 0,
        city_name: '',
        district_id: '',
        district_name: ''
      },
      facilitatorDate: {
        company: '',
        account: '',
        mobile: '',
        province_id:'',
        city_id: '',
        district_id:'',
        endtime: '',
        manager: ''
      },
      ruleValidate: {
        company: [
          { required: true, message: '公司名不能为空', trigger: 'blur' }
        ],
        account: [
          { required: true, message: '联系人不能为空', trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '手机号不能为空', trigger: 'blur' },
          { type: 'string', message: '请输入正确的手机号', pattern: /^1[345789]\d{9}$/}
        ],
        manager: [
            { required: true, message: '业务经理不能为空', trigger: 'blur' }
        ],
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
        //获取服务商的信息和地址列表
        this.$store.dispatch('facilitatorInfo')
            .then((response) => {
                this.value.push(response.info.province_id);
                this.regionInfo.province_id = response.info.province_id;
                this.regionInfo.province_name = response.info.province_name;
                this.facilitatorDate.province_id = response.info.province_id;
                this.labelData = response.info.province_name;
                if(response.info.city_id!=0){
                  this.value.push(response.info.city_id);
                  this.regionInfo.city_id = response.info.city_id;
                  this.facilitatorDate.city_id = response.info.city_id;
                  this.regionInfo.city_name = response.info.city_name;
                  this.labelData += response.info.city_name;
                }
                this.value.push(0);
                this.display = !this.display;
            })
            .catch((error) => {
                this.$Message.error('Fail!');
            });
        this.$store.dispatch('regionList')
            .then((response) => {
                this.cityList = response;
            })
            .catch((error) => {
                this.$Message.error('Fail!');
            });
    },
    onSubmit() {
      this.facilitatorDate.endtime = Date.parse(new Date(this.facilitatorDate.endtime));
      //获取服务商的信息和地址列表
      this.$store.dispatch('facilitatorStore',this.facilitatorDate)
        .then((response) => {
          if(response.status==200)
          {
             this.$Message.success(response.data);
              this.$router.push('/facilitator');
          }else{
              this.$Message.error(response.data);
              this.init();
          }
        })
        .catch((error) => {
          console.log(error)
          this.$Message.error(error);
          this.init();
        });
    },
    handleSubmit (name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.$Message.success('Success!');
        } else {
          this.$Message.error('Fail!');
        }
      })
    }
  },
  // 监听条件变化
  watch: {
    'selectedRegion' (val, oldVal) {
      this.facilitatorDate.province_id = val[0];
      this.facilitatorDate.city_id = val[1];
      this.facilitatorDate.district_id = val[2];
    },
    'endtime' (val, oldVal) {
      console.log(val)
    }
  }
}
</script>