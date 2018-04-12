<template>
    <div>
        <topbar></topbar>
        <router-view></router-view>
    </div>
</template>
<script>
    import { mapActions,mapGetters} from 'vuex';
    import Topbar from './Topbar.vue';

    export default {
        components: {
            Topbar
        },
        mounted () {

        },
        methods:{

        }
    }
</script>