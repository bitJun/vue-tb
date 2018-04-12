<template>
    <div class="h5-container">
        <div class="pay-form-amount">
            <div class="store-info">
                <div class="logo"><img :src="shop.logo"></div>
                <span>向 {{shop.name}} 付款</span>
            </div>
            <div class="pay-info">
                <div class="pay-info-amount">
                    <i class="currency">¥</i>
                    <span v-text="shop.amount"></span>
                </div>
            </div>
            <button v-if="!loading" @click="payHandler" class="btn btn-primary btn-block" :class="[type == 1 ? 'btn-wxpay' : 'btn-alipay']">确认付款</button>
            <button v-if="loading" class="btn btn-primary btn-block disabled">正在支付</button>
            <div class="copyright">
                <a class="if if-modian" href="javascript:;">魔店提供技术支持</a>
            </div>
        </div>
    </div>
</template>
<script>
    import MobileDetect from 'mobile-detect';
    import { Toast } from 'mint-ui';
    import {mapState} from 'vuex'

    export default {
        props: {
            type: {
                type: Number,
                default: 0 //0 支付宝， 1 微信支付
            },
        },
        created() {
            if(this.$route.params.token) {
                this.$store.dispatch('getShopRequest', this.$route.params.token);
            } else {
                Toast('商户不存在');
            }
        },
        computed: {
            ...mapState({
                shop: state => state.shop.shop
            })
        },
        data() {
            return {
                rule: /^\d*\.?\d*$/,
                loading: false
            }
        },
        methods: {
            payHandler() {
                if(this.payCheck()) {
                    this.loading = true;
                    let payCode = 'alipay';
                    let payType = 'wap';
                    const md = new MobileDetect(window.navigator.userAgent);
                    if (md.match('MicroMessenger')) {
                        payCode = 'llpay';
                        payType = 'W';
                    }
                    //创建订单接口
                    this.$store.dispatch('postOrderRequest', {shop_id:this.shop.id, amount:this.shop.amount, pay_code:payCode, pay_type:payType, user_id:this.shop.uid})
                        .then((response) => {
                            $('body').append(response.html);
                            this.loading = false;
                        })
                        .catch((error) => {
                            this.loading = false;
                        });
                }
            },
            payCheck: function() {
                let payAmount = parseFloat(this.shop.amount);
                if (!payAmount) {
                    Toast("请输入正确金额");
                    return false;
                } else if(payAmount < .01) {
                    Toast("支付金额不能小于0.01元");
                    return false;
                } else if(payAmount > 2e5) {
                    Toast("支付金额不能超过200000元");
                    return false;
                }
                return true;
            },
        }
    }
</script>