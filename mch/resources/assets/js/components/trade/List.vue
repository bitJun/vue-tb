<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>收支明细</p>
        </div>
        <Card dis-hover style="margin-bottom:20px;">
            <Form inline style="height:35px;">
                <Form-item>
                    <Input type="text"  placeholder="单号" v-model="paramsData.order_sn">
                    </Input>
                </Form-item>
                <Form-item>
                    <DatePicker @on-change="changeDatePicker" type="datetimerange" placeholder="时间" style="width: 300px"></DatePicker>
                </Form-item>
                <Form-item>
                    <Button type="primary" @click="search">筛选</Button>
                </Form-item>
            </Form>
        </Card>
        <Tabs :value="paramsData.type" @on-click="toggleTab">
            <TabPane label="全部记录" name="all"></TabPane>
            <TabPane label="会员买单" name="0"></TabPane>
            <TabPane label="会员充值" name="1"></TabPane>
            <TabPane label="商户提现" name="2"></TabPane>
        </Tabs>
        <Table size="small" :columns="tradeColumns" :data="tradeData"></Table>
        <div v-if="tradeData.length" style="margin: 10px;overflow: hidden">
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
                tradeColumns: [
                    {
                        title: '时间',
                        key: 'created_at',
                    },
                    {
                        title: '类型 | 单号',
                        render: (h, params) => {
                            return h('a', {
                                'class':'clr-bule',
                                on: {
                                    click: () => {
                                        //this.$router.push({ path: '/member/balance/' + params.row.member_id });
                                    }
                                }
                            }, params.row.type + ' | ' + params.row.order_sn
                            );
                        }
                    },
                    {
                        title: '金额',
                        key: 'cash',
                        render: (h, params) => {
                            return h('a', {
                                'class':'clr-payout'
                            }, Laravel.currencyPrefix + params.row.cash
                            );
                        }
                    }
                ],
/*                tradeData: [
                    {
                        created_at: '2017-09-13 12:11:11',
                        cash: 100,
                        type: '会员买单',
                        order_sn: 'E201709020254563351328'
                    },
                    {
                        created_at: '2017-09-13 12:11:11',
                        cash: 100,
                        type: '会员买单',
                        order_sn: 'E201709020254563351328'
                    },
                    {
                        created_at: '2017-09-13 12:11:11',
                        cash: 100,
                        type: '会员买单',
                        order_sn: 'E201709020254563351328'
                    }
                ],*/
                paramsData: {
                    limit:10,
                    offset:0,
                    mobile:'',
                    level:-1,
                    type:'all',
                    order_sn:'',
                    date_start:'',
                    date_end:''
                }
            }
        },
        created() {
            this.$store.dispatch('getShopBalanceDetails', this.paramsData);
        },
        computed: {
            ...mapState({
                tradeData: state => state.shopBalanceDetail.balanceDetailData,
                count: state => state.shopBalanceDetail.count
            })
        },
        methods:{
            toggleTab: function(name) {
                if(name == 'all') {
                    delete this.paramsData.type;
                } else {
                    this.paramsData.type = name;
                }
                this.$store.dispatch('getShopBalanceDetails', this.paramsData);
            },
            changeDatePicker: function(date) {
                this.paramsData.date_start = date[0];
                this.paramsData.date_end = date[1];
            },
            changePage: function(page){
                this.paramsData.offset = (page-1)*this.paramsData.limit;
                this.$store.dispatch('getShopBalanceDetails', this.paramsData);
            },
            search:function(){
                this.$store.dispatch('getShopBalanceDetails', this.paramsData);
            }
        }
    }
</script>