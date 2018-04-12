<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>二维码</p>
        </div>
        <Row :gutter="20">
            <Col span="12">
                <Card style="min-width:300px;">
                    <div style="text-align:center">
                        <img :src="downloadQrcode">
                        <p>下载二维码</p>
                    </div>
                </Card>
            </Col>
            <Col span="12">
                <Card style="min-width:300px;">
                    <div style="text-align:center">
                        <img :src="cashQrcode">
                        <p>收银二维码</p>
                    </div>
                </Card>
            </Col>
        </Row>
    </div>
</template>
<script>
    import {mapState} from 'vuex';

    export default {
        data() {
            return {

            }
        },
        computed: {
            ...mapState({
                downloadQrcode: state => state.shopQrcode.downloadQrcode,
                cashQrcode: state => state.shopQrcode.cashQrcode
            }),
        },
        created() {
            this.$store.dispatch('getShopQrcodes');
        },
    }
</script>