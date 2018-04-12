<template>
    <div class="facilitator_list">
        <h4 class="title">
        <Button type="primary" class="add">
            <router-link to='/facilitator/add'>添加下级服务商</router-link>
        </Button>
        </h4>
        <div class="list">
        <Card :bordered="true">
            <Input placeholder="公司名称" v-model="paramsData.company_name" style="width: 200px;margin-right: 10px"></Input>
            <Input placeholder="联系人" v-model="paramsData.name" style="width: 200px;margin-right: 10px"></Input>
            <Input placeholder="联系电话" v-model="paramsData.mobile" style="width: 200px;margin-right: 10px"></Input>
            <Button type="primary" @click="search()">筛选</Button>
        </Card>
            <Table border ref="selection" :columns="columns4" :data="shopdata"></Table>
        </div>
        <Pages :count="count" @transferpage="changePage"></Pages>
    </div>
</template>
<script>
let $self = ''
const deleteButton = (vm, h, currentRow, id) => {
    return h('Poptip', {
        props: {
            confirm: true,
            title: '您确定要禁用这个服务商?',
            transfer: true
        },
        on: {
            'on-ok': () => {
                $self.del(id)
            }
        }
    }, [
        h('a', {
            style: {
                margin: '0 5px'
            },
            props: {
                type: 'error',
                placement: 'top'
            }
        }, '禁用')
    ]);
};
import {mapState} from 'vuex'
import Pages from '../common/pages.vue'
export default {
    name: 'facilitator_list',
    data () {
        return {
            columns4: [
                {
                    title: '服务地区',
                    key: 'city'
                },
                {
                    title: '公司名称',
                    key: 'company_name'
                },
                {
                    title: '联系人',
                    key: 'name'
                },
                {
                    title: '联系电话',
                    key: 'mobile'
                },
                {
                    title: '加入时间',
                    key: 'created_at'
                },
                {
                    title: '过期时间',
                    key: 'expire_at'
                },
                {
                    title: '操作',
                    key: 'action',
                    width: 180,
                    align: 'center',
                    render: (h, params) => {
                        const row = params.row;
                        const id = row.id;
                        return h('div', [
                            h('a', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.show(id)
                                    }
                                }
                            }, '查看下级'),
                            h('a', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.edit(id)
                                    }
                                }
                            }, '编辑'),
                            h('a', [
                                deleteButton(this, h, row, id)
                            ])
                        ]);
                    }
                }
            ],
            multipleSelection: [],
            area:'',
            paramsData: {
                company_name: '',
                name: '',
                mobile: '',
                limit: 10,
                offset: 0
            }
        }
    },
    created () {
        $self = this;
        $self.init();
    },
    components: {
        Pages
    },
    computed: {
        ...mapState({
            shopdata: state => state.facilitator.shopdata,
            count: state => state.facilitator.count
        })
    },
    methods: {
        init:function () {
            this.$store.dispatch('facilitatorList', this.paramsData);
        },
        show (id) {
            this.$router.push({name: 'FacilitatorDetail', params: { id: id }})
        },
        edit (id) {
            this.$router.push({name: 'FacilitatorEdit', params: { id: id }})
        },
        toggleSelection(rows) {
            if (rows) {
                rows.forEach(row => {
                    this.$refs.multipleTable.toggleRowSelection(row);
                });
            } else {
                this.$refs.multipleTable.clearSelection();
            }
        },
        handleSelectionChange(val) {
            this.multipleSelection = val;
        },
        changePage: function(offset){
            this.paramsData.offset = (offset-1)*10
            this.$store.dispatch('facilitatorList', this.paramsData);
        },
        search () {
            this.paramsData.offset = 0;
            this.$store.dispatch('facilitatorList', this.paramsData);
        },
        del: function (id) {
            this.$store.dispatch('facilitatorDelete', {id:id})
            .then((response) => {
                if(response.status==200) {
                    this.$Message.success(response.data);
                } else{
                    this.$Message.error(response.data);
                }
                this.init();
            })
            .catch((error) => {
                this.$Message.error(error);
            });
        }
    }
}
</script>
