<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>提现申请</p>
        </div>
        <Card dis-hover style="margin-bottom:20px;">
            <Form inline style="height:35px;">
                <Form-item>
                    <DatePicker @on-change="changeDatePicker" type="datetimerange" placeholder="时间" style="width: 300px"></DatePicker>
                </Form-item>
                <Form-item>
                    <Select placeholder="处理状态" v-model="paramsData.status"  style="width:120px">
                        <Option value="">所有</Option>
                        <Option v-for="(item,key) in statusData" :value="key" :key="key">{{ item }}</Option>
                    </Select>
                </Form-item>
                <Form-item>
                    <Button type="primary" @click="search">筛选</Button>
                </Form-item>
            </Form>
        </Card>
        <Table size="small" :columns="withdrawColumns" :data="withdrawData"></Table>
        <div style="margin: 10px;overflow: hidden">
            <div style="float: right;">
                <Page show-total show-elevator size="small" @on-change="changePage" :page-size="paramsData.limit" :total="count" :current="1"></Page>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        data () {
            return {
                withdrawColumns: [
                    {
                        title: '提现银行',
                        width: '240',
                        render: (h, params) => {
                            return h('p', {
                            }, params.row.bank_name + params.row.brabank_name + ' | ' + params.row.bank_no
                            );
                        }
                    },
                    {
                        title: '提现单号',
                        key: 'withdraw_sn',
                        width: '180'
                    },
                    {
                        title: '提现金额',
                        key: 'amount',
                        render: (h, params) => {
                            return h('a', {
                                'class':'clr-payout'
                            }, Laravel.currencyPrefix + params.row.amount
                            );
                        }
                    },
                    {
                        title: '状态',
                        key: 'status_name',
                        align: 'center'
                    },
                    {
                        title: '申请时间',
                        key: 'created_at',
                        align: 'center'
                    },
                    {
                        title: '处理时间',
                        key: 'finished_at',
                        align: 'center',
                        render: (h, params) => {
                            if(params.row.finished_at == null)
                            {
                                return '-';
                            }else{
                                return params.row.finished_at;
                            }
                        }
                    },
                ],
                /*withdrawData: [
                    {
                        bank_name: '杭州银行',
                        brabank_name: '文一支行',
                        bank_no: '623061****13054619',
                        withdraw_sn: '1020170908022687688',
                        amount: 100,
                        status: '待处理',
                        created_at: '2017-09-13 12:11:11',
                        finished_at: '2017-09-13 12:11:11'
                    }
                ],*/
                statusData: {
                    0: '待打款',
                    1: '打款失败',
                    2: '打款成功'
                },
                paramsData: {
                    limit:10,
                    offset:0,
                    status:'',
                    date_start:'',
                    date_end:''
                }
            }
        },
        created() {
            this.$store.dispatch('getShopWithdrawsRequest', this.paramsData);
            this.$store.dispatch('getShopWithdrawStatusRequest');
        },
        computed: {
            ...mapState({
                withdrawData: state => state.shopWithdraw.withdrawData,
                count: state => state.shopWithdraw.count,
                //statusData: state => state.shopWithdraw.statusData,
            })
        },
        methods:{
            changeDatePicker: function(date) {
                this.paramsData.date_start = date[0];
                this.paramsData.date_end = date[1];
            },
            changePage: function(page){
                this.paramsData.offset = (page-1)*this.paramsData.limit;
                this.$store.dispatch('getShopWithdrawsRequest', this.paramsData);
            },
            search:function(){
                this.$store.dispatch('getShopWithdrawsRequest', this.paramsData);
            }
        }
    }
</script>