<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="ios-circle-outline"></Icon>发布商圈</p>
        </div>
        <TimelineForm type="add"></TimelineForm>
    </div>
</template>
<script>
    import TimelineForm from './../timeline/Form.vue';

    export default {
        components: {
            TimelineForm
        }
    }
</script>