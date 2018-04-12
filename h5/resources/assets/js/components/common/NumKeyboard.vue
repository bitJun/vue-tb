<template>
    <div class="keyboard">
        <ul class="keyboard-nums">
            <li @touchstart="numberHandler('7', $event)">7</li>
            <li @touchstart="numberHandler('8', $event)">8</li>
            <li @touchstart="numberHandler('9', $event)">9</li>
            <li @touchstart="numberHandler('4', $event)">4</li>
            <li @touchstart="numberHandler('5', $event)">5</li>
            <li @touchstart="numberHandler('6', $event)">6</li>
            <li @touchstart="numberHandler('1', $event)">1</li>
            <li @touchstart="numberHandler('2', $event)">2</li>
            <li @touchstart="numberHandler('3', $event)">3</li>
            <li @touchstart="numberHandler('00', $event)">00</li>
            <li @touchstart="numberHandler('0', $event)">0</li>
            <li @touchstart="numberHandler('.', $event)">.</li>
        </ul>
        <ul class="keyboard-btns">
            <li class="if if-del" @touchstart="deleteHandler($event)"></li>
            <li class="if if-clear" @touchstart="clearHandler($event)">清空</li>
            <li v-if="!loading" class="btn-pay" :style="{background:btnPayColor}" @touchstart="payHandler">立即支付</li>
            <li v-if="loading" class="btn-pay btn-paying">正在支付</li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: {
            btnPayColor:{
                type:String,
                default:"#1AAD19"
            },
            loading:{
                type:Boolean,
                default:false
            }
        },
        methods: {
            numberHandler (val, e) {
                e.preventDefault();
                e.stopPropagation();
                this.$emit('numberHandler', val);
            },
            clearHandler (e) {
                e.preventDefault();
                e.stopPropagation();
                this.$emit('clearHandler');
            },
            deleteHandler (e) {
                e.preventDefault();
                e.stopPropagation();
                this.$emit('deleteHandler');
            },
            payHandler () {
                this.$emit('payHandler');
            }
        }
    }
</script>