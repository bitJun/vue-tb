<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>交易设置</p>
        </div>
        <Card class="f12" style="margin-bottom:20px;">
            <p slot="title" style="font-weight:normal;">
                <Icon type="ios-information-outline"></Icon>
                个人银行卡信息
            </p>
            <a href="#" slot="extra" @click.prevent="edit">
                <Icon type="edit"></Icon>
                编辑
            </a>
            <Form class="inner-form" :label-width="120" >
                <Form-item label="银行卡号:" prop="card_no">
                    <span v-text="tradeSettingData.card_no"></span>
                </Form-item>
                <Form-item label="银行绑定手机号:" prop="bind_mob">
                    <span v-text="tradeSettingData.bind_mob"></span>
                </Form-item>
            </Form>
        </Card>
    </div>
</template>
<script>
    export default {
        created() {
            this.$store.dispatch('getLlpayInfoRequest').then(response => {
                if(response.baseInfo) {
                    this.tradeSettingData.mob_bind = response.baseInfo.mob_bind;
                }
                if(response.bankInfo) {
                    this.tradeSettingData.card_no = response.baseInfo.card_no;
                }

            });
        },
        data () {
            return {
                tradeSettingData:{
                    card_no:'',
                    bind_mob:''
                },
            };
        }
    }
</script>