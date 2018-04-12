<template>
    <header class="header">
        <div class="header-wrapper">
            <h1 class="logo">
                <a href="">
                    <img src="/image/logo.png">
                </a>
            </h1>
            <div class="nav">
                <ul class="clearfix pull-left">
                    <li>
                        <router-link to="/member">会员</router-link>
                    </li>
                    <li>
                        <router-link to="/order">订单</router-link>
                    </li>
                    <li>
                        <router-link to="/timeline">商圈</router-link>
                    </li>
                    <li>
                        <router-link to="/setting">设置</router-link>
                    </li>
                </ul>
            </div>
            <div class="user">
                <div class="user-menu">
                    <img class="avatar size34 img-circle" :src="user.shop.logo">
                    <a>{{ user.shop.name }}({{ user.username }})</a>
                    <i class="fa fa-chevron-down"></i>
                    <ul class="dropdown-menu">
<!--                        <li><a>商户信息</a></li>
                        <li><a>管理员设置</a></li>
                        <li><a>个人设置</a></li>
                        <li><a>修改密码</a></li>
                        <li role="separator" class="divider"></li>-->
                        <li><a href="#" @click.prevent="logout()">退出</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
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
            logout() {
                this.$store.dispatch('logoutRequest')
                    .then(() => {
                        this.$router.push({path: '/login'});
                    });
            }
        },
    }
</script>