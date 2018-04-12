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
        <div class="login-module account-mod">
            <div class="module-tab clearfix">
                <h2 class="account-tab">账号登录</h2>
            </div>
            <div class="module-body">
                <div class="account-form">
                    <form role="form" name="form" @submit.prevent="login()">
                        <div class="form-group" :class="{ 'has-error': errors.error}">
                            <span class="help-block">{{ errors.error }}</span>
                        </div>
                        <div class="form-group has-icon" :class="{ 'has-error': errors.username}">
                            <input v-validate="'required'" data-vv-as="用户名" type="text" name="username" class="form-control" placeholder="用户名" v-model="username">
                            <i class="fa fa-user clr-gray"></i>
                            <span class="help-block">{{ errors.username }}</span>
                        </div>
                        <div class="form-group has-icon" :class="{ 'has-error': errors.password}">
                            <input v-validate="'required'" data-vv-as="密码" type="password" name="password" class="form-control" placeholder="密码" v-model="password">
                            <i class="fa fa-lock clr-gray"></i>
                            <span class="help-block">{{ errors.password }}</span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': errors.captcha}">
                            <input v-validate="'required'" data-vv-as="验证码" name="captcha" class="form-control captcha-code" placeholder="验证码" type="text" v-model="captcha">
                            <img @click.prevent="refreshCaptcha()" class="captcha-img" :src="captchaSrc">
                            <span class="help-block">{{ errors.captcha }}</span>
                        </div>
<!--                        <div class="form-group clearfix">
                            <div class="checkbox pull-left">
                                <input id="remember" name="remember" type="checkbox">
                                <label for="remember">
                                    自动登录
                                </label>
                            </div>
                            <a class="forget-pass">忘记密码？</a>
                        </div>-->
                        <button type="submit" class="btn btn-primary btn-block js-submit-btn">登录</button>
                    </form>
                </div>
            </div>
        </div>
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
                username: null,
                password: null,
                captcha: null,
                captchaSrc: '/login/captcha.png?'+ Math.random()
            }
        },
        computed: {
            ...mapState({
                errors: state => state.login.errors
            })
        },
        methods: {
            login() {
                this.$validator.validateAll().then(result => {
                    console.log(result);
                    if (result) {
                        const loginData = {
                            username: this.username,
                            password: this.password,
                            captcha: this.captcha
                        };

                        this.$store.dispatch('loginRequest', loginData)
                            .then((response) => {
                                this.$router.push('/');
                            })
                            .catch((error) => {
                                this.refreshCaptcha();
                            });
                    } else {
                        console.log(this.veeErrors);

                        const failErrors = {
                            username: null,
                            password: null,
                            captcha: null
                        };
                        
                        if(this.veeErrors.has('username')) {
                            failErrors.username = [this.veeErrors.first('username')];
                        }
                        if(this.veeErrors.has('password')) {
                            failErrors.password = [this.veeErrors.first('password')];
                        }
                        if(this.veeErrors.has('captcha')) {
                            failErrors.captcha = [this.veeErrors.first('captcha')];
                        }
                        console.log(failErrors);
                        this.$store.dispatch('loginFailure', failErrors);
                        //if(this.errors.has('username')) {
                            //this.errors.username = '请输入用户名';
                        //}
                    }
                });
            },

            refreshCaptcha() {
                this.captchaSrc = '/login/captcha.png?'+ Math.random();
            }
        }
    }
</script>
