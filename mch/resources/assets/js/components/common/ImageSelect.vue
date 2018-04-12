<template>
    <div class="thumbnail" style="width: 80px;height: 80px;">
        <div class="wb-choice-image" image-space restrict="restrict">
            <a v-if="!src" class="add-image-btn" href="javascript: void(0);"><i class="fa fa-plus"></i>  添加图片</a>
            <div class="has-image" v-if="src">
                <img :src="src">
                <div class="foot-choice">重新选择</div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            src: {
                type: String,
                default: ''
            },
        },
        data() {
            return {

            }
        },
        beforeUpdate() {

        },
        computed: {

        },
        methods: {

        }
    }
</script>