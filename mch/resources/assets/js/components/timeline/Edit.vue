<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="ios-book-outline"></Icon>编辑商圈</p>
        </div>
        <TimelineForm type="edit"></TimelineForm>
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