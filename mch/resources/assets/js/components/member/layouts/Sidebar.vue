<template>
    <div>
        <Menu class="inner-menu" theme="light" :active-name="this.$route.meta.activeSidebar" @on-select="routerLink">
            <Menu-group title="会员管理">
                <Menu-item name="member">
                    <Icon type="ios-list"></Icon>
                    会员列表
                </Menu-item>

                <Menu-item name="member/setting">
                    <Icon type="ios-gear"></Icon>
                    会员设置
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
