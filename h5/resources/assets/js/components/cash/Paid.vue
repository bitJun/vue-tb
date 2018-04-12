<template>
    <div class="h5-container page-paid">
        <div class="paid-info">
            <i class="if if-success"></i>
            <span>付款成功</span>
            <div class="paid-amount">{{ currencyPrefix }}{{ order.amount }}</div>
        </div>
        <ul class="trade-detail">
            <li>
                <label>交易时间</label>
                <div>{{ order.created_at }}</div>
            </li>
            <li>
                <label>交易单号</label>
                <div>{{ order.order_sn }}</div>
            </li>
            <li>
                <label>支付方式</label>
                <div>{{ order.code_name }}</div>
            </li>
        </ul>
        <div v-if="order.commission && order.commission.commission_credit">
            <div v-if="!displaySuccess" class="send-credit">
                <span>恭喜您获得<i>{{ order.commission.commission_credit }}</i>魔豆，下次消费可直接抵用。</span>
                <div class="receive-from">
                    <input v-model="mobile" type="text" placeholder="输入手机号码">
                    <button v-if="!loading" @click="receive" class="btn">领取</button>
                    <button v-if="loading" class="btn disabled">正在领取</button>
                </div>
            </div>
            <div v-if="displaySuccess" class="send-credit">
                <span>恭喜您获得<i>{{ order.commission.commission_credit }}</i>魔豆，下次消费可直接抵用。已经放入魔店账户({{ memberMobile }})<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.taotui8.magicstore">去看看>></a></span>
            </div>
            <mt-popup
                    style="width:100%"
                    v-model="smsPopVisible"
                    position="bottom">
<!--                <input v-if="captcha" type="text" placeholder="请输入图片验证码">
                <img v-if="captcha" :src="captcha">
                <input type="text" placeholder="请输入短信验证码">
                <button class="btn">确定</button>-->
                <div v-if="captchaSrc" class="popup verify-member">
                    <div @click="closePop" class="close-popup if if-close" data-popup=".popup-sku"></div>
                    <div class="popup-title">验证手机</div>
                    <form class="form-horizontal" role="form" onsubmit="return false;">
                        <div class="form-group captcha">
                            <label>图片验证码</label>
                            <input v-model="captcha" type="text" name="captcha" class="form-item consignee-name" placeholder="请填写右侧验证码">
                            <img @click="refreshCaptcha" :src="captchaSrc">
                        </div>
                    </form>
                    <div class="popup-options">
                        <button v-if="!checkLoading" @click="sendVerifyCode" type="button" class="btn btn-block btn-primary">发送验证码</button>
                        <button v-if="checkLoading" type="button" class="btn btn-block disabled">正在发送</button>
                    </div>
                </div>

                <div v-if="!captchaSrc" class="popup verify-member">
                    <div @click="closePop" class="close-popup if if-close" data-popup=".popup-sku"></div>
                    <div class="popup-title">验证手机</div>
                    <form class="form-horizontal" role="form" onsubmit="return false;">
                        <div class="form-group verify-code">
                            <label>短信验证码</label>
                            <input v-model="verifyCode" type="text" name="verify-code" class="form-item consignee-name" placeholder="请填写短信验证码">
                            <button v-if="!displayTimer" @click="reSend" type="button" class="btn-sm btn-warning-o">重新发送</button>
                            <button v-if="displayTimer" type="button" class="btn-sm btn-warning-o">{{ timer }}秒后重发</button>
                        </div>
                    </form>
                    <div class="popup-options">
                        <button v-if="!confirmLoading" @click="confirm" type="button" class="btn btn-block btn-primary">确认</button>
                        <button v-if="confirmLoading" type="button" class="btn btn-block disabled">确认中</button>
                    </div>
                </div>
            </mt-popup>
        </div>
    </div>
</template>
<script>
    import { Toast } from 'mint-ui';

    export default {
        created() {
            this.$store.dispatch('getOrderRequest', {sid:this.$route.params.sid, osn:this.$route.params.osn})
                .then((response) => {
                    this.order = response;
                    if(this.order.mobile) {
                        this.memberMobile = this.order.mobile;
                        this.displaySuccess = true;
                    } else {
                        this.displaySuccess = false;
                    }
                })
                .catch((error) => {

                });
        },
        data() {
            return {
                order:{},
                currencyPrefix: Modian.currencyPrefix,
                smsPopVisible: false,
                mobile: '',
                loading: false,
                checkLoading: false,
                confirmLoading: false,
                captchaSrc: '',
                captcha: '',
                verifyCode: '',
                timer : 60,
                displayTimer: true,
                displaySuccess: false,
                memberMobile: '',
            }
        },
        methods: {
            receive() {
                if(!this.mobile) {
                    Toast('请输入手机号码');
                } else {
                    this.loading = true;
                    this.$store.dispatch('giveCreditRequest', {mobile:this.mobile, order_id:this.order.id, mid:this.order.member_id, shop_id:this.order.shop_id})
                        .then((response) => {
                            if(response.new_user == 1) {
                                if(this.displayTimer && this.timer < 60 && this.timer > 0) {
                                    this.smsPopVisible = true;
                                } else {
                                    this.$store.dispatch('sendVerifyCodeRequest', {mobile:this.mobile, mid: this.order.member_id})
                                        .then((response) => {
                                            if(response.captcha) {
                                                this.refreshCaptcha();
                                            } else {
                                                this.countDown();
                                            }
                                        })
                                        .catch((error) => {
                                        });
                                    this.smsPopVisible = true;
                                }
                            } else {
                                this.memberMobile = this.mobile;
                                this.displaySuccess = true;
                            }
                            this.loading = false;
                        })
                        .catch((error) => {
                            this.loading = false;
                        });
                }
            },
            closePop() {
                this.smsPopVisible = false;
            },
            reSend() {
                if(!this.mobile) {
                    Toast('请输入手机号码');
                } else {
                    this.$store.dispatch('sendVerifyCodeRequest', {mobile: this.mobile, mid: this.order.member_id})
                        .then((response) => {
                            if (response.captcha) {
                                this.captcha = '';
                                this.verifyCode = '';
                                this.refreshCaptcha();
                            }
                        })
                        .catch((error) => {

                        });
                }
            },
            sendVerifyCode() {
                if(!this.captcha) {
                    Toast('请输入图片验证码');
                } else {
                    this.checkLoading = true;
                    this.$store.dispatch('sendVerifyCodeRequest', {mobile:this.mobile,captcha:this.captcha, mid: this.order.member_id})
                        .then((response) => {
                            this.captchaSrc = '';
                            this.countDown();
                            this.checkLoading = false;
                        })
                        .catch((error) => {
                            this.captcha = '';
                            this.verifyCode = '';
                            this.refreshCaptcha();
                            this.checkLoading = false;
                        });
                }
            },
            countDown() {
                this.timer = 60;
                this.displayTimer = true;
                let that = this;
                let interval = window.setInterval(function () {
                    if ((that.timer--) <= 0) {
                        that.timer = 60;
                        that.displayTimer = false;
                        window.clearInterval(interval);
                    }
                }, 1000);
            },
            refreshCaptcha() {
                this.captchaSrc = '/sms/captcha?'+Math.random();
            },
            confirm() {
                this.confirmLoading = true;
                this.$store.dispatch('bindMemberRequest', {mobile:this.mobile,verify_code:this.verifyCode, shop_id:this.order.shop_id, order_id:this.order.id})
                    .then((response) => {
                        this.displaySuccess = true;
                        this.smsPopVisible = false;
                        this.confirmLoading = false;
                        this.memberMobile = this.mobile;
                    })
                    .catch((error) => {
                        this.confirmLoading = false;
                    });
            }
        }
    }
</script>