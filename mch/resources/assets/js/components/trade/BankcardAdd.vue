<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="android-radio-button-off"></Icon>添加银行卡</p>
        </div>
        <BankCardForm type="add"></BankCardForm>
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