<template>
    <Menu mode="horizontal" class="header-wrapper" theme="dark" :active-name="this.$route.meta.activeTopMenu" @on-select="routerLink">
        <div class="layout-logo">
        </div>
        <div class="layout-nav">
            <Menu-item name="member">
                <Icon type="ios-people"></Icon>
                会员
            </Menu-item>
            <Menu-item name="order">
                <Icon type="ios-paper"></Icon>
                订单
            </Menu-item>
            <Menu-item name="timeline">
                <Icon type="aperture"></Icon>
                商圈
            </Menu-item>
            <Menu-item name="trade">
                <Icon type="cash"></Icon>
                交易
            </Menu-item>
            <Menu-item name="setting">
                <Icon type="settings"></Icon>
                设置
            </Menu-item>
        </div>
        <Dropdown class="layout-profile" placement="bottom-end" @on-click="logout()">
            <a href="javascript:void(0)" style="color:#decbab">
                <img class="avatar size34 img-circle" :src="user.shop.logo">
                <a style="color:#decbab;">{{ user.shop.name }} 【{{ user.mobile ? user.mobile : user.username }}】</a>
                <Icon type="arrow-down-b"></Icon>
            </a>
            <Dropdown-menu slot="list">
                <Dropdown-item>退出</Dropdown-item>
            </Dropdown-menu>
        </Dropdown>
    </Menu>
</template>

<script>
    import {mapState} from 'vuex';
    export default {
        created() {
            this.$store.dispatch('setAuthUser');
        },
        computed: {
            ...mapState({
                user: state => state.authUser
            }),
        },
        methods: {
            routerLink(name) {
                this.$router.push({ path: '/'+name });
            },
            logout() {
                this.$store.dispatch('logoutRequest')
                    .then(() => {
                        this.$router.push({path: '/login'});
                    });
            }
        },
    }
</script>