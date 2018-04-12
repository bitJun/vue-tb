<template>
    <div>
        <Menu class="inner-menu" theme="light" active-name="timeline" @on-select="routerLink">
            <Menu-group title="商圈管理">
                <Menu-item name="timeline">
                    <Icon type="ios-list"></Icon>
                    商圈列表
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