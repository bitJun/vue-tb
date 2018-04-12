<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>编辑银行卡</p>
        </div>
        <BankCardForm type="edit"></BankCardForm>
    </div>
</template>
<script>
    import BankCardForm from './../trade/BankCardForm.vue';

    export default {
        components: {
            BankCardForm
        }
    }
</script>