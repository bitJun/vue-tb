<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>交易设置</p>
        </div>
        <Alert show-icon v-if="tradeSettingData.status != 4 && kyc_status == 3">账号审核中...</Alert>
        <Alert type="success" show-icon v-if="tradeSettingData.status != 4 && kyc_status == 4">审核通过</Alert>
        <Alert type="warning" show-icon v-if="tradeSettingData.status != 4 && kyc_status == 2">实名认证不通过</Alert>
        <Alert type="warning" show-icon v-if="tradeSettingData.status != 4 && kyc_status == 5">审核不通过({{fail_reason}})</Alert>
        <Alert type="warning" show-icon v-if="tradeSettingData.status != 4 && kyc_status == 6">证件过期</Alert>
        <Alert type="warning" show-icon v-if="tradeSettingData.status != 4 && kyc_status == 7">待完善</Alert>
        <Card class="f12" style="margin-bottom:20px;">
            <p slot="title" style="font-weight:normal;">
                <Icon type="ios-information-outline"></Icon>
                企业基本信息
            </p>
            <a href="#" slot="extra" @click.prevent="editBase">
                <Icon type="edit"></Icon>
                编辑
            </a>
            <Form class="inner-form" :label-width="130" >
                <Form-item label="法人姓名:" prop="name_user">
                    <span v-text="tradeSettingData.name_user"></span>
                </Form-item>
                <Form-item label="法人身份证号:" prop="no_idcard">
                    <span v-text="tradeSettingData.no_idcard"></span>
                </Form-item>
                <Form-item label="法人身份证有效期:" prop="exp_idcard">
                    <span v-text="tradeSettingData.exp_idcard"></span>
                </Form-item>
                <Form-item label="企业地址:" prop="addr_unit">
                    <span>{{ tradeSettingData.addr_pro }}{{ tradeSettingData.addr_city }}{{ tradeSettingData.addr_district }}{{ tradeSettingData.addr_unit }}</span>
                </Form-item>
                <Form-item label="经营范围:" prop="busi_user">
                    <span v-text="tradeSettingData.busi_user"></span>
                </Form-item>
                <Form-item label="企业名称:" prop="name_unit">
                    <span v-text="tradeSettingData.name_unit"></span>
                </Form-item>
                <Form-item label="营业执照类型:" prop="type_license">
                    <span v-text="tradeSettingData.type_license_str"></span>
                </Form-item>
                <Form-item v-if="tradeSettingData.type_license == 0 || tradeSettingData.type_license == 1" label="组织机构代码:" prop="org_code">
                    <span v-text="tradeSettingData.org_code"></span>
                </Form-item>
                <Form-item v-if="tradeSettingData.type_license == 0 || tradeSettingData.type_license == 1" label="组织机构代码有效期:" prop="exp_orgcode">
                    <span v-text="tradeSettingData.exp_orgcode"></span>
                </Form-item>
                <Form-item label="营业执照号码:" prop="num_license">
                    <span v-text="tradeSettingData.num_license"></span>
                </Form-item>
                <Form-item label="营业执照有效期类型:" prop="type_explicense">
                    <span v-text="tradeSettingData.type_explicense_str"></span>
                </Form-item>
                <Form-item v-if="tradeSettingData.type_explicense == 0" label="营业执照有效期:" prop="exp_license">
                    <span v-text="tradeSettingData.exp_license"></span>
                </Form-item>
                <Form-item label="企业注册类型:" prop="type_register">
                    <span v-text="tradeSettingData.type_register_str"></span>
                </Form-item>
            </Form>
        </Card>
        <Card v-if="tradeSettingData.type_register != 1" class="f12" style="margin-bottom:20px;">
            <p slot="title" style="font-weight:normal;">
                <Icon type="ios-information-outline"></Icon>
                企业对公账户信息
            </p>
            <a href="#" slot="extra" @click.prevent="editBank">
                <Icon type="edit"></Icon>
                编辑
            </a>
            <Form class="inner-form" :label-width="120" >
                <Form-item label="开户行所在地区:" prop="region" >
                    <span>{{ tradeSettingData.bank_pro }}{{ tradeSettingData.bank_city }}{{ tradeSettingData.bank_district }}</span>
                </Form-item>
                <Form-item label="开户银行:" prop="bank_code">
                    <span v-text="tradeSettingData.bank_name"></span>
                </Form-item>
                <Form-item label="开户支行完整名称:" prop="brabank_name">
                    <span v-text="tradeSettingData.brabank_name"></span>
                </Form-item>
                <Form-item label="银行卡号:" prop="card_no">
                    <span v-text="tradeSettingData.card_no"></span>
                </Form-item>
                <Form-item v-if="tradeSettingData.type_register == 1" label="银行绑定手机号:" prop="bind_mob">
                    <span v-text="tradeSettingData.bind_mob"></span>
                </Form-item>
            </Form>
        </Card>
        <Card v-if="tradeSettingData.type_register == 1" class="f12" style="margin-bottom:20px;">
            <p slot="title" style="font-weight:normal;">
                <Icon type="ios-information-outline"></Icon>
                个体工商户银行卡信息
            </p>
            <a href="#" slot="extra" @click.prevent="toBankBard">
                <Icon type="card"></Icon>
                银行卡管理
            </a>
            <Row :gutter="10">
                <Col span="8" v-for="(card,index) in bankCardData" :key="index">
                <Card  :dis-hover="true">
                    <div style="text-align:center">
                        <p>开户行: {{card.bank_name}}{{card.brabank_name}}</p>
                        <p>银行卡号: {{card.bank_no}}</p>
                        <p>银行卡绑定手机号: {{card.bank_mobile}}</p>
                    </div>
                </Card>
                </Col>
            </Row>
        </Card>
        <Card class="f12" style="margin-bottom:20px;">
            <p slot="title" style="font-weight:normal;">
                <Icon type="ios-information-outline"></Icon>
                企业上传照片资料
            </p>
            <a href="#" slot="extra" @click.prevent="editPhoto">
                <Icon type="edit"></Icon>
                编辑
            </a>
            <Row>
                <Col span="7">
                    <Card  :dis-hover="true">
                        <div style="text-align:center">
                            <img style="width: 265px;height:150px;" v-if="tradeSettingData.front_card" :src="tradeSettingData.front_card" class="photo-img">
                            <p v-if="!tradeSettingData.front_card">暂无照片</p>
                            <p style="padding-top:10px">法人身份证正面照片</p>
                        </div>
                    </Card>
                </Col>
                <Col span="1">&nbsp</Col>
                <Col span="7">
                    <Card  :dis-hover="true">
                        <div style="text-align:center">
                            <img style="width: 265px;height:150px;" v-if="tradeSettingData.back_card" :src="tradeSettingData.back_card" class="photo-img">
                            <p v-if="!tradeSettingData.back_card">暂无照片</p>
                            <p style="padding-top:10px">法人身份证反面照片</p>
                        </div>
                    </Card>
                </Col>
                <Col span="1">&nbsp</Col>
                <Col span="7">
                    <Card :dis-hover="true">
                        <div style="text-align:center">
                            <img style="width: 265px;height:150px;" v-if="tradeSettingData.copy_license" :src="tradeSettingData.copy_license" class="photo-img">
                            <p v-if="!tradeSettingData.copy_license">暂无照片</p>
                            <p style="padding-top:10px">营业执照复印件</p>
                        </div>
                    </Card>
                </Col>
            </Row>
            <br>
            <Row>
                <Col span="7">
                <Card v-if="tradeSettingData.type_license == 0" :dis-hover="true">
                    <div style="text-align:center">
                        <img style="width: 265px;height:150px;" v-if="tradeSettingData.copy_org" :src="tradeSettingData.copy_org" class="photo-img">
                        <p v-if="!tradeSettingData.copy_org">暂无照片</p>
                        <p style="padding-top:10px">组织机构代码复印件</p>
                    </div>
                </Card>
                </Col>
                <Col span="1">&nbsp</Col>
                <Col span="7">
                    <Card v-if="tradeSettingData.type_register != 1" :dis-hover="true">
                        <div style="text-align:center">
                            <img style="width: 265px;height:150px;" v-if="tradeSettingData.bank_openlicense" :src="tradeSettingData.bank_openlicense" class="photo-img">
                            <p v-if="!tradeSettingData.bank_openlicense">暂无照片</p>
                            <p style="padding-top:10px">银行开户许可证</p>
                        </div>
                    </Card>
                </Col>
                <Col span="1">&nbsp</Col>
                <Col span="7">
                </Col>
            </Row>
        </Card>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        created() {
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
                    this.tradeSettingData.type_license = response.baseInfo.type_license;
                    this.tradeSettingData.type_license_str = response.baseInfo.type_license_str;
                    this.tradeSettingData.org_code = response.baseInfo.org_code;
                    this.tradeSettingData.exp_orgcode = response.baseInfo.exp_orgcode;
                    this.tradeSettingData.type_register = response.baseInfo.type_register;
                    this.tradeSettingData.type_register_str = response.baseInfo.type_register_str;
                    this.tradeSettingData.type_explicense = response.baseInfo.type_explicense;
                    this.tradeSettingData.type_explicense_str = response.baseInfo.type_explicense_str;
                    this.tradeSettingData.exp_license = response.baseInfo.exp_license;
                    this.tradeSettingData.front_card = response.baseInfo.path_front_card;
                    this.tradeSettingData.back_card = response.baseInfo.path_back_card;
                    this.tradeSettingData.copy_license = response.baseInfo.path_copy_license;
                    this.tradeSettingData.copy_org = response.baseInfo.path_copy_org;
                    this.tradeSettingData.bank_openlicense = response.baseInfo.path_bank_openlicense;
                    this.tradeSettingData.status = response.baseInfo.status;

                    //查询审核状态
                    if(this.tradeSettingData.status != 4)
                    {
                        this.$store.dispatch('getSingleUserQuery').then(response => {
                            if(response)
                            {
                                this.kyc_status = response.kyc_status;
                                this.fail_reason = response.fail_reason;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                    }
                }
                if(response.bankInfo) {
                    this.tradeSettingData.bank_pro = response.bankInfo.bank_pro_str;
                    this.tradeSettingData.bank_city = response.bankInfo.bank_city_str;
                    this.tradeSettingData.bank_district = response.bankInfo.bank_district_str;
                    this.tradeSettingData.bank_name = response.bankInfo.bank_name;
                    this.tradeSettingData.brabank_name = response.bankInfo.brabank_name;
                    this.tradeSettingData.card_no = response.bankInfo.bank_no;
                }

            });

            //个体工商户获取银行卡列表
            this.$store.dispatch('getBanksRequest');
        },
        data () {
            return {
/*                tradeSettingData:{
                    name_user:'徐张生',
                    no_idcard:'330102123120300123123',
                    addr_pro:'浙江省',
                    addr_city:'杭州市',
                    addr_district:'西湖区',
                    addr_unit:'翠柏路7号',
                    busi_user:'电子商务',
                    name_unit:'杭州淘推网络',
                    num_license:'88888888',
                    type_license:2,
                    org_code:'',
                    type_license_name: '多证合一营业执照(不存在独立的组织机构 代码证)(合证合号)',
                    type_register:0,
                    type_register_name: '企业',
                    bank_pro:'浙江省',
                    bank_city:'杭州市',
                    bank_district:'西湖区',
                    bank_name:'中国工商银行',
                    brabank_name:'文一支行',
                    card_no:'6222020102102031123',
                    front_card:'http://oth9z8cjj.bkt.clouddn.com/2017/09/23/FsYO89BAg7O35AUSIvfVm3zFFMQC.jpg',
                    back_card:'http://oth9z8cjj.bkt.clouddn.com/2017/09/23/FsYO89BAg7O35AUSIvfVm3zFFMQC.jpg',
                    copy_license:'http://oth9z8cjj.bkt.clouddn.com/2017/09/23/FsYO89BAg7O35AUSIvfVm3zFFMQC.jpg'
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
                    type_license_str: '',
                    type_register:0,
                    type_register_str: '',
                    bank_pro:'',
                    bank_city:'',
                    bank_district:'',
                    bank_name:'',
                    brabank_name:'',
                    card_no:'',
                    front_card:'',
                    back_card:'',
                    copy_license:''
                },
                kyc_status:0,
                fail_reason:'',
            };
        },
        computed: {
            ...mapState({
                bankCardData: state => state.bank.bankCards
            })
        },
        methods: {
            editBase() {
                this.$router.push({ path: '/trade/setting/base/edit' });
            },
            editBank() {
                this.$router.push({ path: '/trade/setting/bank/edit' });
            },
            toBankBard() {
                this.$router.push({ path: '/trade/bankcard' });
            },
            editPhoto() {
                this.$router.push({ path: '/trade/setting/photo/edit' });
            },
            editBankCard() {
                this.$router.push({ path: '/trade/setting/bankcard/edit' });
            }
        }
    }
</script>