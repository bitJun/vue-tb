<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>企业基本信息修改</p>
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
        <Form v-show="step == 1" class="inner-form" ref="stepForm2" :model="tradeSettingData" :rules="ruleValidate1" :label-width="130" >
            <Form-item label="法人姓名" prop="name_user">
                <Input v-model="tradeSettingData.name_user"></Input>
            </Form-item>
            <Form-item label="法人身份证号" prop="no_idcard">
                <Input v-model="tradeSettingData.no_idcard"></Input>
            </Form-item>
            <Form-item label="法人身份证有效期" prop="exp_idcard">
                <Input v-model="tradeSettingData.exp_idcard" placeholder="例如:20361106"></Input>
            </Form-item>
            <Form-item label="企业所在地区" prop="region_unit" required>
                <RegionPicker :regionData="regions" v-model="tradeSettingData.region_unit"></RegionPicker>
            </Form-item>
            <Form-item label="企业地址" prop="addr_unit">
                <Input v-model="tradeSettingData.addr_unit"></Input>
            </Form-item>
            <Form-item label="经营范围" prop="busi_user">
                <Input v-model="tradeSettingData.busi_user"></Input>
            </Form-item>
            <Form-item label="企业名称" prop="name_unit">
                <Input v-model="tradeSettingData.name_unit"></Input>
            </Form-item>
            <Form-item label="营业执照类型" prop="type_license">
                <RadioGroup v-model="tradeSettingData.type_license">
                    <Radio label="0">
                        <span>普通营业执照</span>
                    </Radio>
                    <Radio label="1">
                        <span>多证合一营业执照(存在独立的组织机构代 码证)(合证不合号)</span>
                    </Radio>
                    <Radio label="2">
                        <span>多证合一营业执照(不存在独立的组织机构 代码证)(合证合号)</span>
                    </Radio>
                </RadioGroup>
            </Form-item>
            <Form-item v-if="tradeSettingData.type_license == 0 || tradeSettingData.type_license == 1" label="组织机构代码" prop="org_code" required>
                <Input v-model="tradeSettingData.org_code"></Input>
            </Form-item>
            <Form-item v-if="tradeSettingData.type_license == 0 || tradeSettingData.type_license == 1" label="组织机构代码有效期" prop="exp_orgcode" required>
                <Input v-model="tradeSettingData.exp_orgcode" placeholder="例如:20361106"></Input>
            </Form-item>
            <Form-item label="营业执照号码" prop="num_license">
                <Input v-model="tradeSettingData.num_license"></Input>
            </Form-item>
            <Form-item label="营业执照有效期类型" prop="type_explicense">
                <RadioGroup v-model="tradeSettingData.type_explicense">
                    <Radio label="0">
                        <span>非永久</span>
                    </Radio>
                    <Radio label="1">
                        <span>永久</span>
                    </Radio>
                </RadioGroup>
            </Form-item>
            <Form-item v-if="tradeSettingData.type_explicense == 0" label="营业执照有效期" prop="exp_license" required>
                <Input v-model="tradeSettingData.exp_license" placeholder="例如:20361106"></Input>
            </Form-item>
            <Form-item label="企业注册类型" prop="type_register">
                <RadioGroup v-model="tradeSettingData.type_register">
                    <Radio label="0">
                        <span>企业</span>
                    </Radio>
                    <Radio label="1">
                        <span>个体工商户</span>
                    </Radio>
                    <Radio label="2">
                        <span>事业单位</span>
                    </Radio>
                    <Radio label="3">
                        <span>社会团体</span>
                    </Radio>
                </RadioGroup>
            </Form-item>
            <Form-item>
                <Button type="default" @click="prev">上一步</Button>
                <Button type="primary" @click="handleSubmit('stepForm2')">提交</Button>
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

            this.$store.dispatch('getLlpayInfoRequest').then(response => {
                if(response.baseInfo) {
                    this.tradeSettingData.name_user = response.baseInfo.name_user;
                    this.tradeSettingData.no_idcard = response.baseInfo.no_idcard;
                    this.tradeSettingData.addr_pro = response.baseInfo.addr_pro_str;
                    this.tradeSettingData.addr_city = response.baseInfo.addr_city_str;
                    this.tradeSettingData.addr_district = response.baseInfo.addr_district_str;
                    this.tradeSettingData.addr_unit = response.baseInfo.addr_unit;
                    this.tradeSettingData.busi_user = response.baseInfo.busi_user;
                    this.tradeSettingData.name_unit = response.baseInfo.name_unit;
                    this.tradeSettingData.num_license = response.baseInfo.num_license;
                    this.tradeSettingData.exp_idcard = response.baseInfo.exp_idcard;
                    this.tradeSettingData.org_code = response.baseInfo.org_code;
                    this.tradeSettingData.type_register = response.baseInfo.type_register;
                    this.tradeSettingData.type_explicense = response.baseInfo.type_explicense;
                    this.tradeSettingData.exp_license = response.baseInfo.exp_license;
                    this.tradeSettingData.front_card = response.baseInfo.front_card;
                    this.tradeSettingData.back_card = response.baseInfo.back_card;
                    this.tradeSettingData.copy_license = response.baseInfo.copy_license;
                    this.tradeSettingData.exp_orgcode = response.baseInfo.exp_orgcode;
                    this.tradeSettingData.region_unit = [{id:response.baseInfo.addr_pro,name:response.baseInfo.addr_pro_str},{id:response.baseInfo.addr_city,name:response.baseInfo.addr_city_str},{id:response.baseInfo.addr_district,name:response.baseInfo.addr_district_str}];
                }
                if(response.bankInfo) {
                    this.tradeSettingData.bank_pro = response.baseInfo.bank_pro_str;
                    this.tradeSettingData.bank_city = response.baseInfo.bank_city_str;
                    this.tradeSettingData.bank_district = response.baseInfo.bank_district_str;
                    this.tradeSettingData.bank_name = response.baseInfo.bank_name;
                    this.tradeSettingData.brabank_name = response.baseInfo.brabank_name;
                    this.tradeSettingData.card_no = response.baseInfo.card_no;
                }

            });
        },
        computed: {
            ...mapState({
                regions: state => state.region.regions,
            }),
        },
        data () {
            const validateRegionUnit = (rule, value, callback) => {
                if(this.tradeSettingData.region_unit.length > 0) {
                    callback();
                } else {
                    callback(new Error('请选择企业所在地区'));
                }
            };
            const validateOrgCode = (rule, value, callback) => {
                if(this.tradeSettingData.type_license == 0 || this.tradeSettingData.type_license == 1){
                    if (!value) {
                        callback(new Error('请输入组织机构代码'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
                }
            };
            const validateExpOrgCode = (rule, value, callback) => {
                if(this.tradeSettingData.type_license == 0 || this.tradeSettingData.type_license == 1){
                    if (!value) {
                        callback(new Error('请输入组织机构代码有效期'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
                }
            };
            const validateExpLicense = (rule, value, callback) => {
                if(this.tradeSettingData.type_explicense == 0 ){
                    if (!value) {
                        callback(new Error('请输入营业执照有效期'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
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
                    name_user: [
                        { required: true, message: '请输入法人姓名', trigger: 'blur' }
                    ],
                    no_idcard: [
                        { required: true, message: '请输入法人身份证号', trigger: 'blur' }
                    ],
                    exp_idcard: [
                        { required: true, message: '请输入法人身份证有效期', trigger: 'blur' }
                    ],
                    region_unit: [
                        { validator: validateRegionUnit, trigger: 'blur' }
                    ],
                    addr_unit: [
                        { required: true, message: '请输入企业地址', trigger: 'blur' }
                    ],
                    busi_user: [
                        { required: true, message: '请输入经营范围', trigger: 'blur' },
                        { type: 'string', max: 150, message: '经营范围最多150个字符', trigger: 'blur' }
                    ],
                    name_unit: [
                        { required: true, message: '请输入企业名称', trigger: 'blur' }
                    ],
                    org_code: [
                        { validator: validateOrgCode, trigger: 'blur' }
                    ],
                    exp_orgcode: [
                        { validator: validateExpOrgCode, trigger: 'blur' }
                    ],
                    num_license: [
                        { required: true, message: '请输入营业执照号码', trigger: 'blur' }
                    ],
                    exp_license: [
                        { validator: validateExpLicense, trigger: 'blur' }
                    ]
                },
                verfiyData: {
                    pwd_pay:'',
                    num_license:''
                },
                /*tradeSettingData:{
                    name_user:'徐张生',
                    no_idcard:'330102123120300123123',
                    addr_pro:'浙江省',
                    addr_city:'杭州市',
                    addr_district:'西湖区',
                    addr_unit:'翠柏路7号',
                    busi_user:'电子商务',
                    name_unit:'杭州淘推网络',
                    type_license:2,
                    type_license_name: '多证合一营业执照(不存在独立的组织机构 代码证)(合证合号)',
                    type_register:0,
                    type_register_name: '企业',
                    num_license: '8888888',
                    selectedRegion: [{id:330000,name:'浙江省'},{id:330100,name:'杭州市'},{id:330106,name:'西湖区'}]
                },*/
                tradeSettingData:{
                    name_user:'',
                    no_idcard:'',
                    addr_pro:'',
                    addr_city:'',
                    addr_district:'',
                    addr_unit:'',
                    busi_user:'',
                    name_unit:'',
                    num_license:'',
                    type_license:0,
                    type_explicense:0,
                    org_code:'',
                    type_register:0,
                    bank_pro:'',
                    bank_city:'',
                    bank_district:'',
                    bank_name:'',
                    brabank_name:'',
                    card_no:'',
                    front_card:'',
                    back_card:'',
                    copy_license:'',
                    region_unit:[],
                    exp_orgcode:''
                },
                step: 0,
                label:'',
                count: '',
                timer: null,
            }
        },
        methods:{
            next(name) {
                console.log('next-baseEdit');
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('postPwdAuthRequest', this.verfiyData)
                            .then((response) => {
                                console.log(response);
                                if(response.status == 0)
                                {
                                    this.$Message.warning(response.result.ret_msg)
                                }else{
                                    this.step += 1;
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                });
            },
            prev() {
                console.log('prev');
                if(this.step > 0) {
                    this.step -= 1;
                }
            },
            handleSubmit(name) {
                console.log('handleSubmitt');
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('putUnitUserRequest', this.tradeSettingData)
                            .then((response) => {
                                console.log(response);
                                if(response.status == 0)
                                {
                                    this.$Message.warning(response.result.message)
                                }else{
                                    this.label = response.message;
                                    this.step += 1;
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
                                console.log(error);
                            });
                    } else {
                        this.loading = false;
                    }
                });
            },
        }
    }
</script>