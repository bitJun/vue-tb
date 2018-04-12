<template>
  <div class="setting_account">
    <Form  ref="bankData" :model="bankData" :rules="ruleValidate"  class="add_form" :label-width="120">
      <h4 class="title">提现信息</h4>
      <FormItem label="开户行所在地" prop="bank_district">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" v-if="display">
          <span v-text="labelData"></span> <span class="caret"></span>
        </button>
        <RegionPicker v-model="selectedRegion" :regionsId="regionInfo" v-if="!display"></RegionPicker>
      </FormItem>
      <FormItem label="开户银行" prop="bank_no">
        <Select v-model="bankData.bank_code" style="width:400px">
          <Option v-for="item in banklist" :value="item.bank_code" :key="item.bank_code">{{ item.bank_name }}</Option>
        </Select>
      </FormItem>
      <FormItem label="开户支行完整名称" prop="brabank_name">
        <Input v-model="bankData.brabank_name" placeholder="请输入开户支行完整名称" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="银行卡号" prop="bank_no">
        <Input v-model="bankData.bank_no" placeholder="请输入银行卡号" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="银行绑定手机号" prop="bank_mobile">
        <Input v-model="bankData.bank_mobile" placeholder="请输入银行绑定手机号" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="开户人姓名" prop="bank_account">
        <Input v-model="bankData.bank_account" placeholder="请输入开户人姓名" style="width: 400px"></Input>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="sure()">保存</Button>
      </FormItem>
    </Form>
  </div>
</template>
<script>
import RegionPicker from '../common/Region.vue';
export default {
  name: 'setting_withdraw',
  data() {
    return {
      display: true,
      labelData: '',
      regionInfo: {
        province_id: 0,
        city_id: 0
      },
      selectedRegion: [],
      bankData: {
        bank_account: '',
        bank_name: '',
        bank_code: '',
        bank_no: '',
        bank_mobile: '',
        bank_province: '',
        bank_city: '',
        bank_district: '',
        brabank_name: ''
      },
      banklist: [],
      ruleValidate: {
        bank_account: [
          { required: true, message: '请输入开户人姓名', trigger: 'blur' }
        ],
        bank_code: [
          { required: true, message: '请选择开户银行', trigger: 'change' }
        ],
        brabank_name: [
          { required: true, message: '请输入开户支行完整名称', trigger: 'blur' }
        ],
        bank_no: [
          { required: true, message: '请输入银行卡号', trigger: 'blur' }
        ],
        bank_mobile: [
          { required: true, message: '请输入银行绑定手机号', trigger: 'blur' }
        ],
        bank_district: [
          { required: true, message: '请选择开户行所在地' }
        ]
      }
    }
  },
  components: {
    RegionPicker
  },
  created () {
    this.init();
    this.getBank();
  },
  methods: {
    init () {
      this.$store.dispatch('WithdrawalsRequest')
        .then((response) => {
          this.bankData = {
            bank_account: response.bank_account,
            bank_name: response.bank_name,
            bank_code: response.bank_code,
            bank_no: response.bank_no,
            bank_mobile: response.bank_mobile,
            bank_province: response.bank_province,
            bank_city: response.bank_city,
            bank_district: response.bank_district,
            brabank_name: response.brabank_name
          }
          this.regionInfo = {
            province_id: response.bank_province,
            province_name: response.province,
            city_id: response.bank_city,
            city_name: response.city,
            district_id: response.bank_district,
            district_name: response.district
          }
          this.labelData = response.province + response.city + response.district
          this.display = !this.display;
        })
        .catch((error) => {
            this.loading = false;
        });
    },
    getBank () {
      this.$store.dispatch('BankListRequest')
        .then((response) => {
          this.banklist = response;
        })
        .catch((error) => {
            this.loading = false;
        });
    },
    sure () {
      this.$store.dispatch('EditWithdrawalsRequest', this.bankData)
        .then((response) => {
          this.$Message.success('保存成功');
          this.init();
        })
        .catch((error) => {
            this.loading = false;
        });
    }
  },
  watch: {
    'selectedRegion' (val, oldVal) {
      this.bankData.bank_province = val[0];
      this.bankData.bank_city = val[1];
      this.bankData.bank_district = val[2];
    },
    'endtime' (val, oldVal) {
      console.log(val)
    }
  }
}
</script>
