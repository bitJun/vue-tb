<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        mounted () {
            this.$Loading.finish();
        },
        created () {
            this.$Loading.start();
            this.$router.beforeEach((to, from, next) => {
                this.$Loading.start();
                next()
            })
            this.$router.afterEach((to, from) => {
                this.$Loading.finish();
        })
        }
    }
</script>