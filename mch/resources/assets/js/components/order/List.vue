<template>
    <div class="container">
        <Card dis-hover style="margin-bottom:20px;">
            <span>今日总收入: </span><span class="clr-money">{{currencyPrefix}}{{dayIncome}}</span>
        </Card>
        <Card dis-hover style="margin-bottom:20px;">
            <Form inline style="height:35px;">
                <Form-item>
                    <Input type="text"  placeholder="订单号" v-model="paramsData.order_sn">
                    </Input>
                </Form-item>
                <Form-item>
                    <Input type="text"  placeholder="手机号" v-model="paramsData.mobile">
                    </Input>
                </Form-item>
                <Form-item>
                    <Select placeholder="订单状态" v-model="paramsData.status" style="width:150px">
                        <Option value="">所有</Option>
                        <Option value="20">待付款</Option>
                        <Option value="40">已付款</Option>
                        <Option value="10">已关闭</Option>
                    </Select>
                </Form-item>
                <Form-item>
                    <Select placeholder="订单类型" v-model="paramsData.type" style="width:150px">
                        <Option value="">所有</Option>
                        <Option value="0">买单</Option>
                        <Option value="1">充值</Option>
                    </Select>
                </Form-item>
                <Form-item>
                    <Button type="primary" @click="search">筛选</Button>
                </Form-item>
            </Form>
        </Card>
        <Table size="small" no-data-text="暂无数据" :columns="orderColumns" :data="orderData"></Table>
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
        data() {
            return {
                orderColumns: [
                    {
                        title: '标题',
                        key: 'title',
                        width: '130px'
                    },
                    {
                        title: '昵称',
                        key: 'nickname',
                    },
                    {
                        title: '手机号',
                        key: 'mobile',
                        width: '110px'
                    },
                    {
                        title: '订单号',
                        key: 'order_sn',
                        width: '230px'
                    },
                    {
                        title: '金额',
                        key: 'amount',
                        width: '120px',
                        render: (h, params) => {
                            return h('a', {
                                'class':'clr-payout'
                            }, Laravel.currencyPrefix + params.row.amount
                            );
                        }
                    },
                    {
                        title: '状态',
                        key: 'status'
                    },
                    {
                        title: '类型',
                        key: 'type'
                    },
                    {
                        title: '下单时间',
                        key: 'created_at',
                        width: '150px'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 80,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.$router.push({ path: '/order/detail/' + params.row.id });
                                        }
                                    }
                                }, '查看')
                            ]);
                        }
                    }
                ],
                paramsData: {
                    limit:10,
                    offset:0,
                    order_sn:'',
                    status:'',
                    type:''
                },
                currencyPrefix: Laravel.currencyPrefix,
            }
        },
        created() {
            this.$store.dispatch('getOrders', this.paramsData);
        },
        computed: {
            ...mapState({
                orderData: state => state.order.orderData,
                count: state => state.order.count,
                dayIncome: state => state.order.dayIncome
            })
        },
        mounted () {

        },
        methods:{
            changePage: function(page){
                this.paramsData.offset = (page-1)*this.paramsData.limit;
                this.$store.dispatch('getOrders', this.paramsData);
            },
            search:function(){
                this.$store.dispatch('getOrders', this.paramsData);
            }
        }
    }
</script>