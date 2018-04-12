<script>
    import Upload from 'iview/src/components/upload/upload.vue';
    export default {
        name: 'MyUpload',
        mixins: [ Upload ],
        props: {
            maxCount: {
                type: Number
            },
            onExceededCount: {
                type: Function,
                default () {
                    return {};
                }
            }
        },
        methods: {
            uploadFiles (files) {
                let postFiles = Array.prototype.slice.call(files);
                const check = (this.fileList.length + postFiles.length) <= this.maxCount && this.maxCount > 0;
                if (!check) {
                    this.onExceededCount(files);
                    const n = this.maxCount - this.fileList.length;
                    if(n > 0) {
                        postFiles = postFiles.slice(0, n);
                    } else {
                        return;
                    }
                }

                if (!this.multiple) postFiles = postFiles.slice(0, 1);

                if (postFiles.length === 0) return;

                postFiles.forEach(file => {
                    this.upload(file);
                });
            },
        },
    };
</script>