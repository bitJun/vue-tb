<template>
    <div class="container">
        <Breadcrumb>
            <BreadcrumbItem href="/order">订单列表</BreadcrumbItem>
            <BreadcrumbItem>订单详情</BreadcrumbItem>
        </Breadcrumb>
        <Card class="f12" style="margin-top:20px">
            <p slot="title" v-text="order.title">
                <Icon type="ios-time-outline"></Icon>
            </p>
            <Form class="inner-form"  :label-width="80">
                <Form-item label="订单号:">
                    <span v-text="order.order_sn"></span>
                </Form-item>
                <Form-item label="昵称:">
                    <span v-text="order.nickname"></span>
                </Form-item>
                <Form-item label="手机号:">
                    <span v-text="order.mobile"></span>
                </Form-item>
                <Form-item label="订单金额:">
                    <span class="clr-payout" >{{ currencyPrefix + order.amount}}</span>
                </Form-item>
                <Form-item label="下单时间:">
                    <span v-text="order.created_at"></span>
                </Form-item>
                <Form-item label="订单状态:">
                    <span v-text="order.status"></span>
                </Form-item>
                <Form-item label="订单类型:">
                    <span v-text="order.type"></span>
                </Form-item>
            </Form>
        </Card>
    </div>
</template>
<script>
    import {mapState} from 'vuex';
    import ImageUpload from '../../components/common/ImageUpload.vue';

    export default {
        data() {
            return {
                currencyPrefix: Laravel.currencyPrefix,
                order:[]
            }
        },
        created() {
            this.$store.dispatch('getOrderRequest', this.$route.params.id).then(response => {
                this.order = response;
            });
        }
    }
</script>
<!--
<script>
    export default {
        data() {
            return {
                currencyPrefix: Laravel.currencyPrefix,
                order: { // 'id','title','order_sn','mobile','amount','status','type','created_at'
                    id:1,
                    title: '韩千炉海鲜自助烤肉充值',
                    order_sn: 'E2017081615028495936268171',
                    nickname: '道儿瑟老',
                    mobile: 13968154713,
                    amount: 100,
                    status : '待付款',
                    type: '买单',
                    created_at: '2017-08-16 10:13:13'
                }
            }
        },
    }
</script>-->
