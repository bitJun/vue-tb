<template>
    <div>
        <Menu class="inner-menu" theme="light" active-name="order" @on-select="routerLink">
            <Menu-group title="订单管理">
                <Menu-item name="order">
                    <Icon type="ios-list"></Icon>
                    订单列表
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