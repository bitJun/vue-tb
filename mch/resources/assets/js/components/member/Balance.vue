<template>
    <div class="container">
        <Breadcrumb>
            <BreadcrumbItem href="/member">会员列表</BreadcrumbItem>
            <BreadcrumbItem>余额记录</BreadcrumbItem>
        </Breadcrumb>

        <Table size="small" highlight-row :columns="columns3" :data="balanceDetailData" style="margin-top:20px"></Table>

        <div v-if="balanceDetailData" style="margin: 10px;overflow: hidden">
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
                columns3: [

                    /*{
                        type: 'index',
                        width: 60,
                        align: 'center',
                        key:'id'
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
                        title:'昵称',
                        key:'nickname'
                    },
                    {
                        title: '变动金额',
                        key: 'cash'
                    },
                    {
                        title: '明细',
                        key: 'subject',
                        width:240
                    },
                    {
                        title: '时间',
                        key: 'created_at'
                    }
                ],
                paramsData: {
                    limit:10,
                    offset:0,
                    member_id:this.$route.params.id,
                    //mobile:'',
                    //level:-1
                },

            }
        },
        created() {
            this.$store.dispatch('getBalanceDetails', this.paramsData);
        },
        computed: {
            ...mapState({
                balanceDetailData: state => state.balanceDetail.balanceDetailData,
                count: state => state.balanceDetail.count
            })
        },
        mounted () {

        },
        methods:{

            changePage: function(page){
                this.paramsData.offset = (page-1)*this.paramsData.limit;
                this.$store.dispatch('getBalanceDetails', this.paramsData);
            },
            search:function(){
                //console.log(this.paramsData);
                this.$store.dispatch('getBalanceDetails', this.paramsData);
            }
        }
    }
</script>
