<template>
    <div>
        <Pay :type="1" v-if="cashType == 0"></Pay>
        <PayWithAmount :type="1" v-if="cashType == 1"></PayWithAmount>
    </div>
</template>
<script>
    import Pay from './../cash/Pay.vue';
    import PayWithAmount from './../cash/PayWithAmount.vue';

    export default {
        components: {
            Pay,
            PayWithAmount
        },
        created() {
            let Base64 = require('js-base64').Base64;
            let params = Base64.decode(this.$route.params.token);
            params = params.split("&");
            let paramsObj = {};
            for (let i = 0; i < params.length; i++) {
                let p = params[i].split('=');
                eval("paramsObj."+p[0]+" = '"+p[1]+"';");
            }
            if(paramsObj.a) {
                this.cashType = 1;
            } else {
                this.cashType = 0;
            }
        },
        data() {
            return {
                cashType:0
            }
        },
    }
</script>