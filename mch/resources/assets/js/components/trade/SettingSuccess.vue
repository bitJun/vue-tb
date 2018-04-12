<template>
    <div class="container">
        <ul style="text-align: center"><Icon type="checkmark-circled" size="100" color="#0bbc0a"></Icon></ul>
        <ul style="text-align: center"><span v-text="label" style="font-size: 16px; color:grey;"></span></ul>
    </div>
</template>
<script>
    export default {
        props: {
            label:''
        },
    }
</script>