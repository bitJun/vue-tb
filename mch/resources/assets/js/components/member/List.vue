<template>
    <div class="container">
        <Card dis-hover style="margin-bottom:20px;">
            <Form inline style="height:35px;">
                <Form-item>
                    <Input type="text"  placeholder="手机号" v-model="paramsData.mobile">
                    </Input>
                </Form-item>
                <Form-item>
                    <Select placeholder="等级" style="width:150px" v-model="paramsData.level">
                        <Option value="0">高级会员</Option>
                        <Option value="1">股东合伙人</Option>
                    </Select>
                </Form-item>
                <Form-item>
                    <Button type="primary" @click="search">筛选</Button>
                </Form-item>
            </Form>
        </Card>


        <Table size="small" :columns="memberColumns" :data="memberData"></Table>


        <div v-if="memberData.length" style="margin: 10px;overflow: hidden">
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
                memberColumns: [
                    /*{
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },*/
                    {
                        title: '头像',
                        key: 'avatar',
                        render: (h, params) => {
                            return h('img', {
                                        attrs: {
                                            src: params.row.avatar
                                        },
                                        style: {
                                            width:'30px',
                                            height:'30px'
                                        }
                                    }
                            );
                        }
                    },
                    {
                        title: '昵称',
                        key: 'nickname'
                    },
                    {
                        title: '手机号',
                        key: 'mobile'
                    },
                    {
                        title: '等级',
                        key: 'level_name'
                    },
                    {
                        title: '魔豆',
                        key: 'credit',
                        render: (h, params) => {
                            return h('a', {
                                    'class':'clr-credit',
                                    on: {
                                        click: () => {
                                            this.$router.push({ path: '/member/credit/' + params.row.member_id });
                                        }
                                    }
                                }, params.row.credit
                            );
                        }
                    },
                    {
                        title: '余额',
                        key: 'balance',
                        render: (h, params) => {
                            return h('a', {
                                    'class':'clr-payin',
                                    on: {
                                        click: () => {
                                            this.$router.push({ path: '/member/balance/' + params.row.member_id });
                                        }
                                    }
                                }, Laravel.currencyPrefix + params.row.balance
                            );
                        }
                    },
/*                    {
                        title: '操作',
                        key: 'action',
                        width: 150,
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
                                            //this.show(params.index)
                                        }
                                    }
                                }, '查看'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            //this.remove(params.index)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }*/
                ],
                paramsData: {
                    limit:10,
                    offset:0,
                    mobile:'',
                    level:-1
                }
            }
        },
        created() {
            this.$store.dispatch('getMembers', this.paramsData);
        },
        computed: {
            ...mapState({
                memberData: state => state.member.memberData,
                count: state => state.member.count
            })
        },
        mounted () {

        },
        methods:{

            changePage: function(page){
                this.paramsData.offset = (page-1)*this.paramsData.limit;
                this.$store.dispatch('getMembers', this.paramsData);
            },
            search:function(){
                //console.log(this.paramsData);
                this.$store.dispatch('getMembers', this.paramsData);
            }
        }
    }
</script>