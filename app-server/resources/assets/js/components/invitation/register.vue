<template>
  <div class="register_view">
    <div class="register_top">
      魔客注册
      <i class="iconfont icon-close48" @click="close()"></i>
    </div>
    <form v-if="isregister">
      <div class="form-group">
        <p class="form-label">手机号</p>
        <input class="form-control" type="text" placeholder="请输入您的手机号码" v-model="data.mobile">
      </div>
      <div class="form-group">
        <p class="form-label">密码</p>
        <input class="form-control" type="password" placeholder="使用6~16位数字和大小写字母组合" v-model="data.password">
      </div>
      <div class="form-group address">
        <p class="form-label">所在地区</p>
        <div class="form-control" @click="choosecity()">{{address}}</div>
        <input class="form-control" type="text" placeholder="请选择所在地" @click="choosecity()" v-model="address">
      </div>
      <div class="form-group">
        <p class="form-label">短信验证码</p>
        <div class="Captcha clearfix">
          <input class="form-control code pull-left" type="text" placeholder="请输入验证码" v-model="data.code">
          <a class="send_code able_send text-center pull-left" v-if="iscode == false" @click="getsms()">获取验证码</a>
          <a class="send_code text-center pull-left disabled" v-if="iscode == true">{{wait}}s后可重发</a>
        </div>
      </div>
      <div class="form-group" v-if="ImgCode">
        <p class="form-label">图形码</p>
        <div class="Captcha clearfix">
          <input class="form-control code pull-left" type="text" placeholder="请输入验证码" v-model="data.captcha">
          <a class="send_code able_send text-center pull-left" @click="reloadCaptcha()">
            <img v-bind:src="captcha">
          </a>
        </div>
      </div>
      <a class="submit" id="submit" @click="submit()">{{btn}}</a>
    </form>
    <div class="tips" v-if="!isregister">
      <div v-html="tips">{{tips}}</div>
      <a class="goback" @click="goback()" v-if="fail">返回重试</a>
      <a class="download" v-bind:href="download_url" v-if="success">立即下载魔客</a>
    </div>
    <City v-bind:class="model"></City>
  </div>
</template>
<script>
import { Toast } from 'mint-ui';
import {mapState} from 'vuex'
import City from './city.vue'
import { Indicator } from 'mint-ui';
export default {
  name: 'register',
  data () {
    return {
      wait: 60,
      iscode: false,
      data: {
        mobile: '',
        password: '',
        province_id: '',
        city_id: '',
        district_id: '',
        code: '',
        captcha: '',
        invitee_moker_id: ''
      },
      isregister: true,
      tips: '',
      fail: true,
      success: false,
      ImgCode: false,
      model: '',
      captcha: '',
      address: '请选择所在地',
      btn: '提交',
      flag: true,
      download_url: ''
    }
  },
  components: {
    City
  },
  created () {
    this.download_url = window.dl_url;
    this.data.invitee_moker_id = this.$route.params.id;
  },
  methods: {
    update () {
      if (this.wait <= 1) {
        this.wait = 60;
        this.iscode = false;
        this.flag = true;
        clearInterval(this.Interval);
      } else {
        this.wait--
      }
    },
    getsms () {
      let phonereg = /^1[34578]\d{9}$/;
      if (!phonereg.test(this.data.mobile)) {
        if (this.data.mobile === '') {
          this.toast('请填写手机号');
        }
        else {
          this.toast('手机号输入有误');
        }
        return false;
      }
      let params = {
        mobile: this.data.mobile
      }
      if (this.ImgCode === true) {
        params.captcha = this.data.captcha;
      }
      if (params.captcha === '' && this.ImgCode === true) {
        this.toast('图形验证码不能为空');
        return false;
      }
      else {
        if (this.flag === false) {
          return false;
        }
        else {
          this.$store.dispatch('postSmsRequest', params)
            .then((response) => {
              if (response.status) {
                this.iscode = true;
                this.flag = false;
                this.Interval = setInterval(this.update, 1000);
              }
              else if (response.status === false && response.url!='') {
                this.iscode = false;
                this.ImgCode = true;
                this.flag = true;
                let stamp = new Date().getTime();
                this.captcha = response.url + '?' + stamp;
              }
            })
            .catch((error) => {
              this.iscode = false;
              this.flag = true;
              Indicator.close();
            });
        }
      }
    },
    reloadCaptcha () {
      let stamp = new Date().getTime();
      this.captcha = this.captcha.split('?')[0]  + '?' + stamp;
    },
    close () {
      this.$parent.close()
    },
    choosecity () {
      this.model = 'city_popup';
      this.$parent.ischoose = true;
    },
    submit () {
      if (this.btn === '提交中...') {
        return false;
      }
      else {
        let phonereg = /^1[34578]\d{9}$/;
        let pwdreg = /^(?=.*?[A-Z])(?=.*?[a-z]).{6,16}$/;
        let params = this.data;
        if (this.ImgCode === false) {
          delete params.captcha;
        }
        if (!phonereg.test(params.mobile)) {
          this.toast('手机号输入有误');
          return false;
        }
        if (!pwdreg.test(params.password)) {
          this.toast('请使用6~16位数字和大小写字母组合');
          return false;
        }
        if (params.city_id === '') {
          this.toast('请选择所在地');
          return false;
        }
        if (params.code === '') {
          this.toast('验证码不能为空');
          return false;
        }
        else {
          Indicator.open();
          this.btn = '提交中...'
          this.$store.dispatch('registerRequest', params)
            .then((response) => {
              console.log(response)
              if (response.id != '') {
                localStorage.setItem('userId', response.id)
                this.tips = `<a class="tip_icon"><img src="/images/success.png"></a>
                <p>恭喜您注册成功！</p>
                <p class="msg_success">您的注册账号：<span>${response.mobile}</span></p>`
                this.fail = false;
                this.success = true;
              } else {
                this.tips = `<a class="tip_icon"><img src="/images/fail.png"></a>
                <p>注册失败</p><p class="msg">注册过程中出现错误，请重试。</p>`
                this.fail = true;
                this.success = false;
              }
              this.isregister = false;
              Indicator.close();
            })
            .catch((response) => {
              this.btn = '提交';
              Indicator.close();
            });
        }
      }
    },
    goback () {
      this.btn = '提交';
      this.success = false;
      this.isregister = true
    },
    toast (msg) {
      Toast({
        message: msg,
        duration: 2000
      });
    }
  }
}
</script>