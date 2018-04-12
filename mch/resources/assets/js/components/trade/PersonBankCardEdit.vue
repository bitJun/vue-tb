<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>个体工商户绑卡信息修改</p>
        </div>
        <div class="inner-box">
            <Steps :current="step">
                <Step title="绑卡认证" icon="iphone"></Step>
                <Step title="绑卡验证" icon="compose"></Step>
                <Step title="提交修改" icon="android-checkbox-outline"></Step>
            </Steps>
        </div>
        <Form v-show="step == 0" class="inner-form" ref="authForm" :model="tradeSettingData" :rules="ruleValidateAuth" :label-width="120" >
            <Form-item label="银行卡号" prop="card_no">
                <Input v-model="tradeSettingData.card_no"></Input>
            </Form-item>
            <Form-item label="银行绑定手机号" prop="bind_mob">
                <Input v-model="tradeSettingData.bind_mob"></Input>
            </Form-item>
            <Form-item>
                <Button type="primary" :loading="loading1" @click="handleAuth('authForm')">
                    <span v-if="!loading1">认证</span>
                    <span v-else>认证中...</span>
                </Button>
            </Form-item>
        </Form>

        <Form v-show="step == 1" class="inner-form" ref="verfyForm" :model="tradeSettingData" :rules="ruleValidateVerfy" :label-width="120">
            <Form-item label="验证码" prop="verify_code">
                <Input v-model="tradeSettingData.verify_code"></Input>
            </Form-item>
            <Form-item>
                <Button type="default" @click="prev">上一步</Button>
                <Button type="primary" :loading="loading2" @click="handleVerfy('verfyForm')">
                    <span v-if="!loading2">验证</span>
                    <span v-else>验证中...</span>
                </Button>
            </Form-item>
        </Form>
    </div>
</template>
<script>
    import {mapState} from 'vuex';

    export default {
        created() {
            this.$store.dispatch('getLlpayInfoRequest').then(response => {
                if(response.bankInfo) {
                    this.tradeSettingData.card_no = response.baseInfo.card_no;
                    this.tradeSettingData.bind_mob = response.baseInfo.bind_mob;
                }

            });
        },
        computed: {
            ...mapState({
                regions: state => state.region.regions,
            }),
        },
        data () {
            return {
                loading1: false,
                loading2: false,
                ruleValidateAuth: {
                    card_no: [
                        { required: true, message: '请输入银行卡号', trigger: 'blur' }
                    ],
                    bind_mob: [
                        { required: true, message: '请输入银行绑定手机号', trigger: 'blur' }
                    ]
                },
                ruleValidateVerfy: {
                    verify_code: [
                        { required: true, message: '请输入验证码', trigger: 'blur' }
                    ]
                },
                tradeSettingData:{
                    verify_code:''
                },
                step: 0,
            }
        },
        methods:{
            handleAuth(name) {
                this.loading1 = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.step += 1;
                    } else {
                        this.loading1 = false;
                    }
                });
            },
            prev() {
                if(this.step > 0) {
                    this.step -= 1;
                }
            },
            handleVerfy(name) {
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