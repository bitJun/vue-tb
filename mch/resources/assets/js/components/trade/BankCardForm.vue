<template>
    <div>
        <div class="inner-box">
            <Steps :current="step">
                <Step title="填写信息" icon="compose"></Step>
                <Step title="绑卡验证" icon="android-checkbox-outline"></Step>
                <Step title="提交成功" icon="android-checkmark-circle"></Step>
            </Steps>
        </div>

        <Form v-show="step == 0" class="inner-form" ref="bankCardForm" :model="bankCardData" :rules="ruleValidate" :label-width="120" >
            <Form-item label="开户行所在地区" prop="region" required >
                <RegionPicker :regionData="regions" v-model="bankCardData.region"></RegionPicker>
            </Form-item>
            <Form-item label="开户银行" prop="bank_code">
                <Select v-model="bankCardData.bank_code" filterable>
                    <Option v-for="(item, index) in banks" :value="index" :key="item">{{ item }}</Option>
                </Select>
            </Form-item>
            <Form-item label="开户支行完整名称" prop="brabank_name">
                <Input v-model="bankCardData.brabank_name"></Input>
            </Form-item>
            <Form-item label="银行卡号" prop="bank_no">
                <Input v-model="bankCardData.bank_no"></Input>
            </Form-item>
            <Form-item label="银行绑定手机号" prop="bank_mobile">
                <Input v-model="bankCardData.bank_mobile"></Input>
            </Form-item>
            <Form-item label="开户人姓名" prop="bank_account">
                <Input v-model="bankCardData.bank_account"></Input>
            </Form-item>
            <Form-item>
                <Button type="primary" @click="next('bankCardForm')" v-if="!loading">
                    下一步
                </Button>
                <Button v-else>提交中...</Button>
            </Form-item>
        </Form>

        <Form v-show="step == 1" class="inner-form" ref="verfyForm" :model="bankCardData" :rules="ruleValidate1" :label-width="120">
            <Form-item label="验证码" prop="verify_code">
                <Input v-model="bankCardData.verify_code"></Input>
            </Form-item>
            <Form-item>
                <Button type="default" @click="prev">上一步</Button>
                <Button type="primary" :loading="loading" @click="handleVerfy('verfyForm')">
                    <span v-if="!loading">验证</span>
                    <span v-else>验证中...</span>
                </Button>
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
            SettingSuccess
        },
        props:{
            type:{
                type:String,
                default:'add', //add:新增,edit:编辑
            }
        },
        created() {
            this.$store.dispatch('getRegions');
            this.$store.dispatch('getBanks');
            if(this.type == 'edit') {
                this.$store.dispatch('getBankRequest', this.$route.params.id).then(response => {
                    this.bankCardData = response;
                });
            }
        },
        computed: {
            ...mapState({
                regions: state => state.region.regions,
                banks: state => state.bank.banks
            }),
        },
        data () {
            const validateRegion = (rule, value, callback) => {
                if(this.bankCardData.region.length > 0) {
                    callback();
                } else {
                    callback(new Error('请选择开户行所在地区'));
                }
            };
            return {
                step:0,
                label:'',
                loading: false,
                loading1: false,
                uploadAction: Laravel.uploadAction,
                ruleValidate: {
                    region: [
                        { validator: validateRegion, trigger: 'blur' }
                    ],
                    brabank_name: [
                        { required: true, message: '请输入开户支行完整名称', trigger: 'blur' }
                    ],
                    bank_code: [
                        { required: true, message: '请选择开户银行', trigger: 'blur' }
                    ],
                    bank_no: [
                        { required: true, message: '请输入银行卡号', trigger: 'blur' }
                    ],
                    bank_mobile: [
                        { required: true, message: '请输入银行绑定手机号', trigger: 'blur' }
                    ],
                    bank_account: [
                        { required: true, message: '请输入开户人姓名', trigger: 'blur' }
                    ]
                },
                ruleValidate1: {
                    verify_code: [
                        { required: true, message: '请输入验证码', trigger: 'blur' }
                    ]
                },
                bankCardData:{
                    id:0,
                    bank_name:'',
                    bank_no:'',
                    bank_mobile:'',
                    bank_account:'',
                    brabank_name:'',
                    region:[]
                }
            }
        },
        methods:{
            prev() {
                if(this.step > 0) {
                    this.step -= 1;
                    this.loading = false;
                }
            },
            next(name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        if(this.type == 'add'){
                            this.$store.dispatch('addBankRequest', this.bankCardData)
                                .then((response) => {
                                    this.loading = false;
                                    if(!response.status){
                                        this.$Message.warning(response.message);
                                        return false;
                                    }
                                    if(response.authResult.token){
                                        this.step += 1;
                                        this.bankCardData.id = response.data.id;
                                    }else{
                                        this.$Message.warning(response.authResult.ret_msg);
                                        return false;
                                    }
                                })
                                .catch((error) => {
                                    this.loading = false;
                                });
                        }else if(this.type == 'edit') {
                            this.$store.dispatch('editBankRequest', this.bankCardData)
                                .then((response) => {
                                    this.loading = false;
                                    if(!response.status){
                                        this.$Message.warning(response.message);
                                        return false;
                                    }
                                    if(!response.authResult){
                                        this.step += 2;
                                    }
                                    if(response.authResult.token){
                                        this.step += 1;
                                    }else{
                                        this.$Message.warning(response.authResult.ret_msg);
                                        return false;
                                    }
                                    //this.$Message.success('保存成功');
                                    //this.$router.push('/trade/bankcard');
                                    //this.step += 1;
                                })
                                .catch((error) => {
                                    this.loading = false;
                                });
                        }

                    }
                });
            },
            handleVerfy(name){
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {

                        this.$store.dispatch('bankcardAuthVerfyRequest', this.bankCardData)
                            .then((response) => {
                                this.loading = false;

                                this.step += 1;
                                this.label = response.message;
                            })
                            .catch((error) => {
                                this.loading = false;
                            });

                    } else {
                        this.loading = false;
                    }
                });
            },
        }
    }
</script>