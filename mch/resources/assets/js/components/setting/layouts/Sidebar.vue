<template>
    <div>
        <Menu class="inner-menu" theme="light" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
            <Menu-group title="设置管理">
                <Menu-item name="setting/shop" >
                    <Icon type="ios-gear"></Icon>
                    商户设置
                </Menu-item>
                <Menu-item name="setting/credit">
                    <Icon type="ios-gear"></Icon>
                    魔豆设置
                </Menu-item>
                <Menu-item name="setting/password">
                    <Icon type="ios-gear"></Icon>
                    修改密码
                </Menu-item>
                <Menu-item name="setting/qrcode">
                    <Icon type="qr-scanner"></Icon>
                    二维码
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
                console.log(name);
                this.$router.push({ path: '/'+name });
            }
        },
    }
</script>