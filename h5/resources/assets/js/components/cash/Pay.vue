<template>
    <div class="h5-container">
        <div class="pay-form">
            <div class="store-info">
                <div class="logo"><img :src="shop.logo"></div>
                <span v-text="shop.name"></span>
            </div>
            <div class="pay-info">
                <div class="pay-info-amount">
                    <span>付款金额</span>
                    <div class="amount-input">
                        <i class="currency">¥</i>
                        <span v-text="amount"></span>
                        <i class="cursor"></i>
                    </div>
                </div>
            </div>
            <div class="pay-remark">
                <input type="text" placeholder="添加备注" v-model="memo">
            </div>
            <div class="copyright">
                <a class="if if-modian" href="javascript:;">魔店提供技术支持</a>
            </div>
        </div>
        <NumKeyboard
                :loading="loading"
                :btnPayColor="payColor"
                @numberHandler="numberHandler"
                @deleteHandler="deleteHandler"
                @clearHandler="clearHandler"
                @payHandler="payHandler"
        ></NumKeyboard>
    </div>
</template>
<script>
    import NumKeyboard from '../../components/common/NumKeyboard.vue';
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
        components: {
            NumKeyboard
        },
        created() {
            if(this.type == 1) {
                this.payColor = '#1AAD19';
            }
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
                payColor:'#5599ff',
                amount:'',
                rule: /^\d*\.?\d*$/,
                loading: false,
                memo:''
            }
        },
        methods: {
            numberHandler(value) {
                if(this.rule.test(this.amount+value)) {
                    if((this.amount === '0' || this.amount === '00') && value !== ".") {
                        this.amount = "";
                    } else if(this.amount === "" && value === ".") {
                        this.amount = "0";
                    }
                    let newAmount = this.amount + value;
                    newAmount = this.preCheck(newAmount);
                    if(!newAmount) {
                        return;
                    }
                    this.amount = newAmount;
                }
            },
            deleteHandler() {
                let nl = this.amount.length - 1;
                this.amount = this.amount.substring(0, nl);
            },
            clearHandler() {
                this.amount = '';
            },
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
                    this.$store.dispatch('postOrderRequest', {shop_id:this.shop.id, amount:this.amount, memo:this.memo, pay_code:payCode, pay_type:payType})
                     .then((response) => {
                         $('body').append(response.html);
                         this.loading = false;
/*                         const md = new MobileDetect(window.navigator.userAgent);
                         if (md.match('MicroMessenger')) {
                             if(md.match('Android')){
                                 window.androidShare.getWXPayParams(JSON.stringify(response.html));
                             }else{
                                 window.webkit.messageHandlers.appWXPay.postMessage(JSON.stringify(response.html));
                             }
                         } else {
                             $('body').append(response.html);
                         }*/
                    })
                    .catch((error) => {
                        this.loading = false;
                    });
                }
            },
            preCheck: function(value) {
                let valueArr = value.toString().split(".")
                    , dl = valueArr[1] || "";
                if(!(dl.length > 2) && value > 2e5) {
                    Toast("支付金额不能超过200000");
                    return false;
                } else if(dl.length > 2) {
                    let nl = value.length - (dl.length - 2);
                    value = value.substring(0, nl);
                }
                return value;
            },
            payCheck: function() {
                let payAmount = parseFloat(this.amount);
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