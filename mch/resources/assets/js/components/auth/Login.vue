<template>
<div class="auth-container">
    <div class="login-slider">
        <div class="slider-items">
            <div class="slider-rrd">
                <div class="item" style="display: block;">
                    <a style="background-image: url('/image/bg1.jpg');"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="login-page clearfix">
        <Card style="width:280px">
            <p slot="title" style="text-align:center;color:#2d8cf0">账号登录</p>
            <Form ref="loginData" :model="loginData">
                <Form-item v-if="errors.error" :class="{ 'ivu-form-item-error': errors.error}">
                    <div class="ivu-form-item-error-tip">{{ errors.error }}</div>
                </Form-item>
                <Form-item prop="username" :class="{ 'ivu-form-item-error': errors.username}">
                    <Input type="text" v-model="loginData.username" placeholder="用户名或者手机号">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                    </Input>
                    <div v-if="errors.username" class="ivu-form-item-error-tip">{{ errors.username }}</div>
                </Form-item>
                <Form-item prop="password" :class="{ 'ivu-form-item-error': errors.password}">
                    <Input type="password" v-model="loginData.password" placeholder="密码">
                    <Icon type="ios-locked-outline" slot="prepend"></Icon>
                    </Input>
                    <div v-if="errors.password" class="ivu-form-item-error-tip">{{ errors.password }}</div>
                </Form-item>
                <Form-item prop="captcha" :class="{ 'ivu-form-item-error': errors.captcha}">
                    <Row :gutter="8">
                        <Col span="14"><Input type="text" v-model="loginData.captcha" placeholder="验证码"></Input></Col>
                        <Col span="10"><img @click.prevent="refreshCaptcha()" class="captcha-img" :src="captchaSrc"></Col>
                    </Row>
                    <div v-if="errors.captcha" class="ivu-form-item-error-tip">{{ errors.captcha }}</div>
                </Form-item>
                <Form-item>
                    <Button html-type="submit" type="primary" long @click="login('loginData')">提交</Button>
                </Form-item>
            </Form>
        </Card>
    </div>
    <div class="login-footer">
        <a href=""> ©2017 - 2018 淘推网络 All Rights Reserved. </a>
    </div>
</div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        created() {
            this.$store.dispatch('clearLoginErrors');
        },
        data() {
            return {
                captchaSrc: '/login/captcha.png?'+ Math.random(),
                loginData: {
                    username: '',
                    password: '',
                    captcha: ''
                },
                ruleValidate: {
                    username: [
                        { required: true, message: '请输入用户名或者手机号', trigger: 'blur'}
                    ],
                    password: [
                        { required: true, message: '请输入密码', trigger: 'blur' }
                    ],
                    captcha: [
                        { required: true, message: '请输入验证码', trigger: 'blur' }
                    ]
                }
            }
        },
        computed: {
            ...mapState({
                errors: state => state.login.errors
            })
        },
        methods: {
            login(name) {
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        this.$store.dispatch('loginRequest', this.loginData)
                            .then((response) => {
                                this.$router.push('/');
                            })
                            .catch((error) => {
                                this.refreshCaptcha();
                            });
                    } else {

                    }
                });
            },

            refreshCaptcha() {
                this.captchaSrc = '/login/captcha.png?'+ Math.random();
            }
        }
    }
</script>
