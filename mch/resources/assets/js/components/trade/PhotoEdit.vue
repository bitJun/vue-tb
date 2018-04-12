<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>企业上传照片资料编辑</p>
        </div>
        <Form ref="tradeSettingData" :model="tradeSettingData" :rules="ruleValidate">
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
                    <Col  v-if="tradeSettingData.type_license == 0" span="1">&nbsp</Col>
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
                <Button type="primary" :loading="loading" @click="handleSubmit('tradeSettingData')">
                    <span v-if="!loading">提交</span>
                    <span v-else>提交中...</span>
                </Button>
            </Form-item>
        </Form>
    </div>
</template>
<script>
    import {mapState} from 'vuex';
    import * as api from './../../config';
    import PhotoUpload from '../../components/common/PhotoUpload.vue';

    export default {
        components: {
            PhotoUpload
        },
        beforeCreate() {
            this.$store.dispatch('getLlpayInfoRequest').then(response => {
                if(response.baseInfo) {
                    this.tradeSettingData.type_license = response.baseInfo.type_license;
                    this.tradeSettingData.type_register = response.baseInfo.type_register;
                    /*this.tradeSettingData.front_card = {img:response.baseInfo.front_card,url:response.baseInfo.path_front_card};
                    this.tradeSettingData.back_card = {img:response.baseInfo.back_card,url:response.baseInfo.path_bank_openlicense};
                    this.tradeSettingData.copy_license = {img:response.baseInfo.copy_license,url:response.baseInfo.path_copy_license};
                    this.tradeSettingData.copy_org ={img:response.baseInfo.copy_org,url:response.baseInfo.path_copy_org};
                    this.tradeSettingData.bank_openlicense = {img:response.baseInfo.bank_openlicense,url:response.baseInfo.path_bank_openlicense};

                    this.$refs.frontCardUpload.initImg(this.tradeSettingData.front_card);
                    this.$refs.backCardUpload.initImg(this.tradeSettingData.back_card);
                    this.$refs.copyLicenseUpload.initImg(this.tradeSettingData.copy_license);
                    this.$refs.copyOrgUpload.initImg(this.tradeSettingData.copy_license);
                    this.$refs.bankOpenlicenseUpload.initImg(this.tradeSettingData.bank_openlicense);*/
                }
            });
        },
        data () {
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
                loading: false,
                ruleValidate: {
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
                    org_code:'',
                    type_license_name: '',
                    type_register:0,
                    type_register_name: '',
                    bank_pro:'',
                    bank_city:'',
                    bank_district:'',
                    bank_name:'',
                    brabank_name:'',
                    card_no:'',
                    region_unit:[]
                },
                step: 0,
            }
        },
        computed: {
            ...mapState({
                shop_id: state => state.authUser.shop_id
            }),
        },
        methods:{
            handleSubmit(name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('uploadUnitPhotoRequest', this.tradeSettingData)
                            .then((response) => {
                                //console.log(response);
                                if(!response.status)
                                {
                                    this.$Message.warning(response.result.ret_msg);
                                    this.personLoading1 = false;
                                }else{
                                    let path = '/trade/setting/view';
                                    this.$router.push({ path: path})
                                    console.log('ok');
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
        }
    }
</script>