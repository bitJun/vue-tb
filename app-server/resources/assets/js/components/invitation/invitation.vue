<template>
  <div class="share_view">
    <img src="../../../images/bg.png">
    <p class="invite">
      <span>
        {{data.name}}{{data.mobile}}
      </span>
      邀请您加入魔客
    </p>
    <a class="join_us" @click="register()">
      <img src="../../../images/button.png">
    </a>
    <div class="privilege">
      <ul>
        <li>
          <label>1</label>添加魔店商铺获得交易流水佣金；
        </li>
        <li>
          <label>2</label>邀请下级魔客获得奖励；
        </li>
        <li>
          <label>3</label>终身免费享受魔店商学院课程；
        </li>
      </ul>
    </div>
    <Register v-if="goregister"></Register>
    <div class="popup-overlay" v-bind:class="block" @click="close()"></div>
  </div>
</template>
<script>
import Register from './register.vue'
import { Indicator } from 'mint-ui';
import { mapState } from 'vuex'
export default {
  name: 'invitation',
  data () {
    return {
      data: {
        name: '',
        mobile: ''
      },
      block: '',
      goregister: false,
      ischoose: false
    }
  },
  created () {
    let userId = this.$store.state.userId;
    document.body.style.background="#ffcc00";
    let id = this.$route.params.id;
    Indicator.open();
    this.$store.dispatch('getmokerinfoRequest', {id:id})
      .then((res) => {
          this.data.name = '(' + res.name + ')';
          this.data.mobile = res.mobile;
          let u = navigator.userAgent, app = navigator.appVersion;
          let isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1;
          let isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
          if(isAndroid){
            window.dl_url = res.android_url;
          }
          else if(isiOS){
            window.dl_url = res.ios_url
          }
          if (userId != null && userId !='') {
            window.location.href = window.dl_url
          }
          Indicator.close();
      })
      .catch((error) => {
          Indicator.close();
      });
  },
  components: {
    Register
  },
  methods: {
    register () {
      this.block = 'modal-overlay-visible'
      this.goregister = true
    },
    close () {
      if (this.ischoose === true) {
        return false
      }
      else {
        this.block = ''
        this.goregister = false
      }
    }
  },
  watch: {
    'block' (val, oldVal) {
      if (val !== '') {
        document.body.style.overflow = 'hidden'
      }
      else {
        document.body.style.overflow = 'auto'
      }
    }
  }
}
</script>