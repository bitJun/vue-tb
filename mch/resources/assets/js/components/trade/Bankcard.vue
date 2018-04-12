<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>银行卡管理</p>
        </div>
        <Row>
            <Col span="24">
            <Button type="primary" v-if="bankCardData.length < 3" :loading="loading" @click="addBankCard" style="float:left">
                <span>新增银行卡</span>
            </Button>
            <Alert type="warning" show-icon style="float:left;margin-left: 10px;">最多只能新增3张银行卡</Alert>
            </Col>
        </Row>
        <br>
        <Table size="small" :columns="bankCardColumns" :data="bankCardData"></Table>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        data () {
            return {
                loading: false,
                bankCardColumns: [
                    {
                        title: '开户行',
                        render: (h, params) => {
                            return h('p', {
                            }, params.row.bank_name + params.row.brabank_name
                            );
                        }
                    },
                    {
                        title: '卡号',
                        key: 'bank_no'
                    },
                    {
                        title: '开户人',
                        key: 'bank_account',
                    },
                    {
                        title: '手机号',
                        key: 'bank_mobile',
                    },
                    {
                        title:'绑卡状态',
                        render: (h, params) => {
                            return h('p', {
                            }, params.row.status ? '已绑定' : '未绑定'
                            );
                        }
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 120,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.$router.push({ path: '/trade/bankcard/edit/' + params.row.id });
                                        }
                                    }
                                }, '编辑'),
                                h('Poptip', {
                                    props: {
                                        title: '确认删除？',
                                        confirm: true,
                                        placement: "top-end",
                                        slot: "extra",
                                        width: "200"
                                    },
                                    on: {
                                        'on-ok': () => {
                                            this.deleteBankCard(params.row.id);
                                        },
                                    }
                                }, [
                                    h('Button', {
                                        props: {
                                            type: 'text',
                                            size: 'small'
                                        }
                                    }, '删除'),
                                ])
                            ]);
                        }
                    }
                ],
                /*bankCardData: [
                    {
                        id:1,
                        bank_name: '杭州银行',
                        brabank_name: '文一支行',
                        bank_no: '623061****13054619',
                        bank_account: '李白'
                    },
                    {
                        id:2,
                        bank_name: '工商银行',
                        brabank_name: '文一支行',
                        bank_no: '623061****13054619',
                        bank_account: '李白'
                    }
                ]*/
            }
        },
        created() {
            this.$store.dispatch('getBanksRequest');
        },
        computed: {
            ...mapState({
                bankCardData: state => state.bank.bankCards
            })
        },
        methods:{
            addBankCard:function(){
                this.loading = true;
                this.$router.push({ path: '/trade/bankcard/add' });
            },
            deleteBankCard:function(id){
                this.$store.dispatch('deleteBankRequest', {'id':id})
                    .then((response) => {
                        this.loading = false;
                        this.$Message.success('删除成功');
                        this.$store.dispatch('getBanksRequest');
                    })
                    .catch((error) => {
                        this.loading = false;
                    });
            }
        }
    }
</script>