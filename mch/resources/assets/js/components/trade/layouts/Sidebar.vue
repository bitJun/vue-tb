<template>
    <div>
        <Menu class="inner-menu" theme="light" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
            <Menu-group title="交易管理">
                <Menu-item name="trade/dashboard" >
                    <Icon type="pie-graph"></Icon>
                    交易概况
                </Menu-item>
                <Menu-item name="trade/list">
                    <Icon type="ios-paper"></Icon>
                    收支明细
                </Menu-item>
                <!--<Menu-item name="trade/withdraw">-->
                    <!--<Icon type="ios-list"></Icon>-->
                    <!--提现申请-->
                <!--</Menu-item>-->
                <!--<Menu-item name="trade/bankcard">
                    <Icon type="card"></Icon>
                    银行卡管理
                </Menu-item>-->
                <Menu-item name="trade/setting">
                    <Icon type="ios-gear"></Icon>
                    交易设置
                </Menu-item>
            </Menu-group>
        </Menu>
        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        methods: {
            routerLink(name) {
                this.$router.push({ path: '/'+name });
            }
        },
    }
</script>