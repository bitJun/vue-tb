<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>企业对公账户信息修改</p>
        </div>
        <div class="inner-box">
            <Steps :current="step">
                <Step title="密码验证" icon="ios-unlocked"></Step>
                <Step title="修改信息" icon="compose"></Step>
                <Step title="提交修改" icon="android-checkbox-outline"></Step>
            </Steps>
        </div>
        <Form v-show="step == 0" class="inner-form" ref="stepForm1" :model="verfiyData" :rules="ruleValidate" :label-width="120" >
            <Form-item label="支付密码" prop="pwd_pay">
                <Input type="password" v-model="verfiyData.pwd_pay"></Input>
            </Form-item>
            <Form-item label="营业执照号码" prop="num_license">
                <Input v-model="verfiyData.num_license"></Input>
            </Form-item>
            <Form-item>
                <Button type="primary" @click="next('stepForm1')">
                    下一步
                </Button>
            </Form-item>
        </Form>

        <Form v-show="step == 1" class="inner-form" ref="stepForm2" :model="tradeSettingData" :rules="ruleValidate1" :label-width="120" >
            <Form-item label="开户行所在地区" prop="region" required >
                <RegionPicker :regionData="regions" v-model="tradeSettingData.region_bank"></RegionPicker>
            </Form-item>
            <Form-item label="开户银行" prop="bank_code">
                <Select v-model="tradeSettingData.bank_code" filterable>
                    <Option v-for="(item, index) in banks" :value="index" :key="item">{{ item }}</Option>
                </Select>
            </Form-item>
            <Form-item label="开户支行完整名称" prop="brabank_name">
                <Input v-model="tradeSettingData.brabank_name"></Input>
            </Form-item>
            <Form-item label="银行卡号" prop="card_no">
                <Input v-model="tradeSettingData.card_no"></Input>
            </Form-item>
            <Form-item>
                <Button type="default" @click="prev">上一步</Button>
                <Button v-if="tradeSettingData.type_register != 1" type="primary" :loading="loading" @click="handleSubmit('stepForm2')">
                    <span v-if="!loading">提交</span>
                    <span v-else>提交中...</span>
                </Button>
                <Button v-if="tradeSettingData.type_register == 1" type="primary" @click="nextAuth('stepForm2')">下一步</Button>
            </Form-item>
        </Form>
        <SettingSuccess v-show="step == 2" :label="label"></SettingSuccess>
    </div>
</template>
<script>
    import {mapState} from 'vuex';
    import RegionPicker from '../../components/common/RegionPicker.vue';
    import SettingSuccess from './../trade/SettingSuccess.vue';

    export default {
        components: {
            RegionPicker,
            SettingSuccess,
        },
        created() {
            this.$store.dispatch('getRegions');
            this.$store.dispatch('getBanks');

            this.$store.dispatch('getLlpayInfoRequest').then(response => {
                if(response.baseInfo) {
                    this.tradeSettingData.type_register = response.baseInfo.type_register;
                }
                if(response.bankInfo) {
                    this.tradeSettingData.bank_name = response.bankInfo.bank_name;
                    this.tradeSettingData.brabank_name = response.bankInfo.brabank_name;
                    this.tradeSettingData.card_no = response.bankInfo.bank_no;
                    this.tradeSettingData.bank_code = response.bankInfo.bank_code;
                    this.tradeSettingData.region_bank = [{id:response.bankInfo.bank_province,name:response.bankInfo.bank_pro_str},{id:response.bankInfo.bank_city,name:response.bankInfo.bank_city_str},{id:response.bankInfo.bank_district,name:response.bankInfo.bank_district_str}];
                }
            });
        },
        computed: {
            ...mapState({
                regions: state => state.region.regions,
                banks: state => state.bank.banks
            }),
        },
        data () {
            const validateRegion = (rule, value, callback) => {
                if(this.tradeSettingData.region_bank.length > 0) {
                    callback();
                } else {
                    callback(new Error('请选择开户行所在地区'));
                }
            };
            return {
                loading: false,
                ruleValidate:{
                    pwd_pay: [
                        { required: true, message: '请输入支付密码', trigger: 'blur' }
                    ],
                    num_license: [
                        { required: true, message: '请输入营业执照号码', trigger: 'blur' }
                    ],
                },
                ruleValidate1: {
                    region: [
                        { validator: validateRegion, trigger: 'blur' }
                    ],
                    brabank_name: [
                        { required: true, message: '请输入开户支行完整名称', trigger: 'blur' }
                    ],
                    bank_code: [
                        { required: true, message: '请选择开户银行', trigger: 'blur' }
                    ],
                    card_no: [
                        { required: true, message: '请输入银行卡号', trigger: 'blur' }
                    ]
                },
                verfiyData: {
                    pwd_pay:'',
                    num_license:''
                },
                /*tradeSettingData:{
                    type_register: 1,
                    bank_code:'03080000',
                    brabank_name:'文一支行',
                    card_no:'62220203020340203402',
                    region_bank: [{id:330000,name:'浙江省'},{id:330100,name:'杭州市'},{id:330106,name:'西湖区'}]
                },*/
                tradeSettingData:{
                    type_register: 1,
                    bank_code:'',
                    brabank_name:'',
                    card_no:'',
                    region_bank: []
                },
                step: 0,
                label:'',
                count: '',
                timer: null,
            }
        },
        methods:{
            next(name) {
                console.log('next-bankEdit');
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('postPwdAuthRequest', this.verfiyData)
                            .then((response) => {
                                console.log(response);
                                if(response.status == 0)
                                {
                                    this.$Message.warning(response.result.ret_msg)
                                }else{
                                    this.label = response.message;
                                    this.step += 1;
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                });
            },
            nextAuth(name) {
                console.log('nextAuth');
                this.step += 1;
            },
            prev() {
                console.log('prev');
                if(this.step > 0) {
                    this.step -= 1;
                }
            },
            handleSubmit(name) {
                console.log('handleSubmit2222');
                //this.step = 4;
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('putUnitUserAcctRequest', this.tradeSettingData)
                            .then((response) => {
                                console.log(response);
                                if(response.status == 0)
                                {
                                    this.$Message.warning(response.result.ret_msg)
                                }else{
                                    this.step += 1;
                                    this.$Message.success('修改成功');
                                    //倒计时跳转
                                    const TIME_COUNT = 3;
                                    if (!this.timer) {
                                        this.count = TIME_COUNT;
                                        this.timer = setInterval(() => {
                                            if (this.count > 0 && this.count <= TIME_COUNT) {
                                                this.count--;
                                            } else {
                                                this.$router.push({ path: '/trade/setting/view' });
                                                clearInterval(this.timer);
                                                this.timer = null;
                                            }
                                        }, 1000)
                                    }
                                }
                            })
                            .catch((error) => {
                                this.loading = false;
                                console.log(error);
                            });
                    } else {
                        this.loading = false;
                    }
                });
            },
            handleVerfy(name) {
                console.log('handleVerfy');
                this.loading2 = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.step += 1;
                    } else {
                        this.loading2 = false;
                    }
                });
            },
        }
    }
</script>