<template>
	<div class="login_view">
		<Card class="login_main" :bordered="false">
			<p slot="title">
				<Icon type="log-in"></Icon>
				欢迎登录
			</p>
			<Form ref="logindata" class="login-con" :model="logindata">
				<Form-item v-if="errors.error" :class="{ 'ivu-form-item-error': errors.error}">
                    <div class="ivu-form-item-error-tip">{{ errors.error }}</div>
                </Form-item>
		        <FormItem prop="mobile">
					<Input v-model="logindata.mobile" placeholder="请输入手机号">
						<span slot="prepend">
							<Icon :size="16" type="person"></Icon>
						</span>
					</Input>
		        </FormItem>
		        <FormItem prop="password">
		            <Input type="password" v-model="logindata.password" placeholder="密码">
						<span slot="prepend">
							<Icon :size="14" type="locked"></Icon>
						</span>
					</Input>
		        </FormItem>
		        <FormItem prop="captcha">
		            <Input v-model="logindata.captcha" placeholder="验证码" style="width:158px;margin-right: 5px" />
		            <img @click.prevent="refreshCaptcha()" class="codeImg" :src="captchaImg">
					<div v-if="errors.captcha" class="ivu-form-item-error-tip">{{ errors.captcha }}</div>
		        </FormItem>
				<FormItem>
					<Button @click="login('logindata')" type="primary" long>登录</Button>
				</FormItem>
		    </Form>
		</Card>
	</div>
</template>
<script>
import {mapState} from 'vuex';
export default {
	name: 'login',
	data () {
		return {
			size: {
				left: '',
				top: ''
			},
			logindata: {
				mobile: '',
				password: '',
				captcha: ''
			},
			captchaImg: '/login/captcha.png?'+ Math.random(),
			ruleValidate: {
                mobile: [
                    { required: true, message: '手机号不能为空', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '密码不能为空', trigger: 'blur' }
                ],
                captcha: [
                    { required: true, message: '验证码不能为空', trigger: 'change' }
                ]
            }
		}
	},
	created() {
		this.$store.dispatch('clearLoginErrors');
	},
	computed: {
		...mapState({
			errors: state => state.login.errors
		})
	},
	mounted () {
		let $self = this;
		document.onkeydown = function (e) {
      		if (e && e.keyCode === 13) {
        		$self.login('logindata')
      		}
    	}
	},
	methods: {
		login (name) {
			this.$refs[name].validate((valid) => {
                if (valid) {
                    this.$store.dispatch('loginRequest', this.logindata)
						.then((response) => {
							this.$router.push('/');
						})
						.catch((error) => {
							this.refreshCaptcha();
						});
                } else {
                }
            })
		},
		refreshCaptcha() {
			this.captchaImg = '/login/captcha.png?'+ Math.random();
		},
		errtips (msg) {
			this.$Message.error(msg);
		}
	}
}
</script>