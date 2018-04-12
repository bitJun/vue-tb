<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>交易概况</p>
        </div>
        <Row>
            <Col span="11">
            <Card dis-hover>
                <p slot="title">我的收入</p>
                <p class="clr-money">{{ currencyPrefix }}{{ income }}</p>
            </Card>
            </Col>
            <Col span="12" offset="1">
            <Card dis-hover>
                <p slot="title">待结算</p>
                <p class="clr-money">{{ currencyPrefix }}{{ expect }}</p>
            </Card>
            </Col>
        </Row>
        <Modal v-model="modal" width="400" @on-ok="confirm">
            <p slot="header" class="clr-orange">
                <Steps :current="current" size="small" style="padding-right: 20px;">
                    <Step title="选择银行卡"></Step>
                    <Step title="输入金额"></Step>
                </Steps>
            </p>

            <p v-if="bankCardData.length == 0"><Icon type="document"></Icon> 您还未添加银行卡，请先<a href="/trade/bankcard">添加银行卡</a></p>

            <RadioGroup v-if="current == 0" v-model="selectedBank"  vertical style="margin-left: 25px;">

                <Radio :label="bank.id"  v-for="(bank, index) in bankCardData" :key="index">
                    <span>{{ bank.bank_name }}({{ bank.bank_no }})</span>
                </Radio>

            </RadioGroup>
            <div v-if="current == 1" style="text-align:center">
                <Input :placeholder="placeholder" v-model="amount" style="width: 300px"></Input>
            </div>
            <div slot="footer">
                <Button v-if="current == 0 && bankCardData.length > 0" type="primary" size="small"  @click="next">下一步</Button>
                <Button v-if="current == 1" type="primary" size="small"  @click="prev">上一步</Button>
                <Button v-if="current == 1" type="primary" size="small" :loading="loading" @click="confirm">确定</Button>
            </div>
        </Modal>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                currencyPrefix: Laravel.currencyPrefix,
                modal: false,
                loading: false,
                current: 0,
                selectedBank: '',
                limit:2,
                amount:'0.00',
                balance:'0.00',
                expect:'0.00',
                income:'0.00',
                placeholder:''
            }
        },
        created() {
            this.$store.dispatch('getShopRequest').then(response => {
                this.shopData = response;
                this.balance = this.shopData.balance ? this.shopData.balance : '0.00';
                this.amount = this.shopData.balance ? this.shopData.balance : '0.00';
                this.income = this.shopData.income ? this.shopData.income : '0.00';
                this.expect = this.shopData.expect ? this.shopData.expect : '0.00';
                this.placeholder = '最高可提现金额：' + this.amount;
            });
        },
        computed: {
            ...mapState({
                bankCardData: state => state.bank.bankCards
            })
        },
        methods: {
            handleWithdraw: function() {
                this.modal = true;
                this.current = 0;
                this.$store.dispatch('getBanksRequest').then(response => {
                    //console.log(response.length);
                    if(response.length){
                        this.selectedBank = response[0].id;
                    }

                });
                //this.selectedBank = 105;
            },
            next: function() {
                if(!this.selectedBank){
                    this.$Message.error('请选择要提现的银行卡');
                    return false;
                }
                this.current += 1;
            },
            prev: function() {
                this.current -= 1;
                this.loading = false;
            },
            confirm: function() {
                this.loading = true;

                this.$store.dispatch('addShopWithdrawRequest', {'bank_id':this.selectedBank,'amount':this.amount})
                    .then((response) => {
                        //console.log(response);
                        this.loading = false;
                        this.$Message.success('提现成功');
                        this.$router.push('/trade/withdraw');
                    })
                    .catch((error) => {
                        this.loading = false;
                    });

            }
        }
    }
</script>