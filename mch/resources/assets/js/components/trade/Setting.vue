<template>
    <div v-show="domLoaded" class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>交易设置</p>
        </div>
        <div v-show="tradeSettingData.type == 0">
            <div class="inner-box">
                <Steps :current="step">
                    <Step title="短信验证" icon="iphone"></Step>
                    <Step title="填写信息" icon="compose"></Step>
                    <Step title="上传照片" icon="camera"></Step>
                    <Step title="提交注册" icon="android-checkbox-outline"></Step>
                </Steps>
            </div>
            <SMSVerify v-show="step == 0" :on-handle="smsNext"></SMSVerify>
            <Form v-show="step == 1" class="inner-form" ref="tradeSettingData" :model="tradeSettingData" :rules="ruleValidate" :label-width="130" >
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
                <Form-item v-if="tradeSettingData.type_register != 1" label="开户行所在地区" prop="region_bank" required >
                    <RegionPicker :regionData="regions" v-model="tradeSettingData.region_bank"></RegionPicker>
                </Form-item>
                <Form-item v-if="tradeSettingData.type_register != 1" label="开户银行" prop="bank_code" required>
                    <Select v-model="tradeSettingData.bank_code" filterable>
                        <Option v-for="(item, index) in banks" :value="index" :key="item">{{ item }}</Option>
                    </Select>
                </Form-item>
                <Form-item v-if="tradeSettingData.type_register != 1" label="开户支行完整名称" prop="brabank_name" required >
                    <Input v-model="tradeSettingData.brabank_name"></Input>
                </Form-item>
                <Form-item v-if="tradeSettingData.type_register != 1" label="银行卡号" prop="card_no" required >
                    <Input v-model="tradeSettingData.card_no"></Input>
                </Form-item>
                <Form-item label="提现密码" prop="pwd_pay">
                    <Input v-model="tradeSettingData.pwd_pay" placeholder="该密码用于提现，不是银行卡密码，请设置一个新密码。" type="password"></Input>
                </Form-item>
                <Form-item>
                    <Button type="default" @click="prev">上一步</Button>
                    <Button type="primary" @click="next('tradeSettingData')">下一步</Button>
                </Form-item>
            </Form>

            <Form v-show="step == 2" ref="tradeSettingData1" :model="tradeSettingData" :rules="ruleValidate1">
                <Form-item label="">
                    <Row>
                        <Col span="7">
                        <FormItem prop="front_card">
                            <PhotoUpload :shop-id="shop_id" ref="frontCardUpload" v-model="tradeSettingData.front_card" :action="qiniuUploadAction" label="法人身份证正面照片"></PhotoUpload>
                        </FormItem>
                        </Col>
                        <Col span="1">&nbsp</Col>
                        <Col span="7">
                        <FormItem prop="back_card">
                            <PhotoUpload :shop-id="shop_id" ref="backCardUpload" v-model="tradeSettingData.back_card" :action="qiniuUploadAction" label="法人身份证反面照片"></PhotoUpload>
                        </FormItem>
                        </Col>
                        <Col span="1">&nbsp</Col>
                        <Col span="7">
                        <FormItem prop="copy_license">
                            <PhotoUpload :shop-id="shop_id" ref="copyLicenseUpload" v-model="tradeSettingData.copy_license" :action="qiniuUploadAction" label="营业执照复印件"></PhotoUpload>
                        </FormItem>
                        </Col>
                    </Row>
                    <br>
                    <Row>
                        <Col span="7">
                        <FormItem prop="copy_org">
                            <PhotoUpload v-if="tradeSettingData.type_license == 0" :shop-id="shop_id" ref="copyOrgUpload" v-model="tradeSettingData.copy_org" :action="qiniuUploadAction" label="组织机构代码复印件"></PhotoUpload>
                        </FormItem>
                        </Col>
                        <Col v-if="tradeSettingData.type_license == 0" span="1">&nbsp</Col>
                        <Col span="7">
                        <FormItem prop="bank_openlicense">
                            <PhotoUpload v-if="tradeSettingData.type_register != 1" :shop-id="shop_id" ref="bankOpenlicenseUpload" v-model="tradeSettingData.bank_openlicense" :action="qiniuUploadAction" label="银行开户许可证"></PhotoUpload>
                        </FormItem>
                        </Col>
                        <Col v-if="tradeSettingData.type_register != 1" span="1">&nbsp</Col>
                        <Col span="7">
                        </Col>
                    </Row>
                </Form-item>
                <Form-item>
                    <Button type="default" @click="prev">上一步</Button>
                    <Button type="primary" :loading="loading" @click="handleSubmit('tradeSettingData1')">
                        <span v-if="!loading">提交</span>
                        <span v-else>提交中...</span>
                    </Button>
                </Form-item>
            </Form>
            <SettingSuccess v-show="step == 3" :label="label"></SettingSuccess>

<!--            <Form v-show="tradeSettingData.type_register == 1 && step == 3" class="inner-form" ref="tradeSettingData2" :model="tradeSettingData" :rules="ruleValidate2" :label-width="120" >
                <Form-item label="银行卡号" prop="card_no">
                    <span v-text="tradeSettingData.card_no"></span>
                </Form-item>
                <Form-item label="银行绑定手机号" prop="bind_mob">
                    <Input v-model="tradeSettingData.bind_mob"></Input>
                </Form-item>
                <Form-item>
                    <Button type="primary" :loading="personLoading1" @click="handleSubmit1('tradeSettingData2')">
                        <span v-if="!personLoading1">认证</span>
                        <span v-else>认证中...</span>
                    </Button>
                </Form-item>
            </Form>

            <Form v-show="tradeSettingData.type_register == 1 && step == 4" class="inner-form" ref="tradeSettingData3" :model="tradeSettingData" :rules="ruleValidate3" :label-width="120">
                <Form-item label="验证码" prop="verify_code">
                    <Input v-model="tradeSettingData.verify_code"></Input>
                </Form-item>
                <Form-item>
                    <Button type="default" @click="personPrev">上一步</Button>
                    <Button type="primary" :loading="personLoading2" @click="handleSubmit2('tradeSettingData3')">
                        <span v-if="!personLoading2">验证</span>
                        <span v-else>验证中...</span>
                    </Button>
                </Form-item>
            </Form>-->
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex';
    import RegionPicker from '../../components/common/RegionPicker.vue';
    import PhotoUpload from '../../components/common/PhotoUpload.vue';
    import SMSVerify from './../trade/SMSVerify.vue';
    import SettingSuccess from './../trade/SettingSuccess.vue';
    import * as api from './../../config';

    export default {
        components: {
            RegionPicker,
            PhotoUpload,
            SMSVerify,
            SettingSuccess
        },
        beforeCreate() {
            this.$store.dispatch('getRegions');
            this.$store.dispatch('getBanks');
            this.$store.dispatch('getLlpayInfoRequest').then(response => {
                if(response.isDone) {
                    let path = '/trade/setting/view';
                    this.$router.push({ path: path})
                }
                //判断是否用户已存在，如果存在直接pass发短信这一步
                if(response.baseInfo)
                {
                    //查询连连信息接口
                    this.$store.dispatch('getSingleUserQuery').then(response => {
                        if(response)
                        {
                            let path = '/trade/setting/view';
                            this.$router.push({ path: path})
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                }
                this.domLoaded = true;
            });
        },
        computed: {
            ...mapState({
                regions: state => state.region.regions,
                banks: state => state.bank.banks,
                shop_id: state => state.authUser.shop_id
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
            const validateRegionBank = (rule, value, callback) => {
                if(this.tradeSettingData.type_register != 1) {
                    if(this.tradeSettingData.region_bank.length > 0) {
                        callback();
                    } else {
                        callback(new Error('请选择开户行所在地区'));
                    }
                } else {
                    callback();
                }
            };
            const validateBankInfo = (rule, value, callback) => {
                if(this.tradeSettingData.type_register != 1) {
                    if(!value) {
                        let err = '';
                        if(rule.field == 'brabank_name') {
                            err = '请输入开户支行完整名称';
                        } else if(rule.field == 'bank_code') {
                            err = '请选择开户银行';
                        } else if(rule.field == 'card_no') {
                            err = '请输入银行卡号';
                        }
                        callback(new Error(err));
                    } else {
                        callback();
                    }
                } else {
                    callback();
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
            const validatePhoto = (rule, value, callback) => {
                if (!value) {
                    let err = '';
                    if(rule.field == 'front_card') {
                        err = '请上传法人身份证正面照片';
                    } else if(rule.field == 'back_card') {
                        err = '请上传法人身份证反面照片';
                    } else if(rule.field == 'copy_license') {
                        err = '请上传营业执照复印件';
                    }
                    callback(new Error(err));
                } else {
                    callback();
                }
            };
            const validateCopyOrg = (rule, value, callback) => {
                if(this.tradeSettingData.type_license == 0){
                    if (!value) {
                        callback(new Error('请上传组织机构代码复印件'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
                }
            };
            const validateBankOpenlicense = (rule, value, callback) => {
                if(this.tradeSettingData.type_register != 1){
                    if (!value) {
                        callback(new Error('请上传营业执照复印件'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
                }
            };
            return {
                qiniuUploadAction: api.qiniuUploadAction,
                domLoaded: false,
                defaultImg: {img:'2017/09/23/FsYO89BAg7O35AUSIvfVm3zFFMQC.jpg',url:'http://oth9z8cjj.bkt.clouddn.com/2017/09/23/FsYO89BAg7O35AUSIvfVm3zFFMQC.jpg'},
                loading: false,
                personLoading1: false,
                personLoading2: false,
                uploadAction: Laravel.uploadAction,
                ruleValidate: {
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
                    ],
                    region_bank: [
                        { validator: validateRegionBank, trigger: 'blur' }
                    ],
                    brabank_name: [
                        { validator: validateBankInfo, trigger: 'blur' }
                    ],
                    bank_code: [
                        { validator: validateBankInfo, trigger: 'blur' }
                    ],
                    card_no: [
                        { validator: validateBankInfo, trigger: 'blur' }
                    ],
                    pwd_pay: [
                        { required: true, message: '请输入提现密码', trigger: 'blur' }
                    ],
                },
                ruleValidate1: {
                    front_card: [
                        {validator: validatePhoto, trigger: 'blur'}
                    ],
                    back_card: [
                        {validator: validatePhoto, trigger: 'blur'}
                    ],
                    copy_license: [
                        {validator: validatePhoto, trigger: 'blur'}
                    ],
                    copy_org: [
                        {validator: validateCopyOrg, trigger: 'blur'}
                    ],
                    bank_openlicense: [
                        {validator: validateBankOpenlicense, trigger: 'blur'}
                    ],
                },
                ruleValidate2: {
                    card_no: [
                        { required: true, message: '请输入银行卡号', trigger: 'blur' }
                    ],
                    bind_mob: [
                        { required: true, message: '请输入银行绑定手机号', trigger: 'blur' }
                    ]
                },
                ruleValidate3: {
                    verify_code: [
                        { required: true, message: '请输入验证码', trigger: 'blur' }
                    ]
                },
                /*tradeSettingData:{
                    type:0,
                    name_user:'小李',
                    no_idcard:'422801198810091322',
                    exp_idcard:'20201220',
                    busi_user:'互联网',
                    name_unit:'小微',
                    num_license:'38377377378289923',
                    type_license:0,
                    type_explicense:0,
                    exp_orgcode:'20361106',
                    exp_license:'20361122',
                    org_code:'vs90000',
                    type_register:0,
                    verify_code:'',
                    region_bank:[
                        {id:330000,listorder:11,name:'浙江省',parent_id:1},
                        {id:330100,listorder:1,name:'杭州市',parent_id:330000},
                        {id:330106,listorder:12,name:'西湖区',parent_id:330100},
                    ],
                    region_unit:[
                        {id:330000,listorder:11,name:'浙江省',parent_id:1},
                        {id:330100,listorder:1,name:'杭州市',parent_id:330000},
                        {id:330106,listorder:12,name:'西湖区',parent_id:330100},
                    ],
                    addr_unit:'7号',

                    brabank_name:'西湖支行',
                    card_no:'622500999999919',
                    bank_code:'01020000',
                    pwd_pay:'123456',
                },*/
                tradeSettingData:{
                    type:0,
                    name_user:'',
                    no_idcard:'',
                    exp_idcard:'',
                    busi_user:'',
                    name_unit:'',
                    num_license:'',
                    type_license:0,
                    type_explicense:0,
                    exp_orgcode:'',
                    exp_license:'',
                    org_code:'',
                    type_register:0,
                    verify_code:'',
                    region_bank:[],
                    region_unit:[],
                    addr_unit:'',
                    brabank_name:'',
                    card_no:'',
                    bank_code:'',
                    pwd_pay:'',
                },
                step: 0,
                personStep: 0
            }
        },
        methods:{
            smsNext() {
                console.log('smsNext');
                this.step += 1;
            },
            next(name) {
                console.log('next');
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('postOpensmsunituserRequest', this.tradeSettingData)
                            .then((response) => {
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
            personPrev() {
                console.log('personPrev');
                if(this.personStep > 0) {
                    this.personStep -= 1;
                    this.personLoading1 = false;
                }
            },
            handleSubmit(name) {
                console.log('handleSubmit1111');
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('uploadUnitPhotoRequest', this.tradeSettingData)
                            .then((response) => {
                                //console.log(response);
                                if(response.status == 0)
                                {
                                    this.$Message.warning(response.result.ret_msg);
                                    this.personLoading1 = false;
                                }else{
                                    console.log('ok');
                                    this.step += 1;
                                    this.loading = false;
                                }
                            })
                            .catch((error) => {
                                this.loading = false;
                                //console.log(error);
                            });
                    } else {
                        this.loading = false;
                    }
                });
            },
            handleSubmit2(name) {
                console.log('handleSubmit2');
                this.personLoading2 = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('postBankcardAuthVerfyRequest', this.tradeSettingData)
                            .then((response) => {
                                //console.log(response);
                                if(response.status == 0)
                                {
                                    this.$Message.warning(response.result.ret_msg)
                                }else{
                                    this.personStep += 1;
                                }
                            })
                            .catch((error) => {
                                this.personLoading2 = false;
                                //console.log(error);
                            });
                    } else {
                        this.personLoading2 = false;
                    }
                });
            }
        }
    }
</script>