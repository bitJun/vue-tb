<template>
    <div>
        <div class="demo-upload-list" v-for="item in uploadList">
            <template v-if="item.status === 'finished'">
                <img :src="item.url+'-timeline.thumb'">
                <div class="demo-upload-list-cover">
                    <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
                    <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                </div>
            </template>
            <template v-else>
                <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
            </template>
        </div>
        <MyUpload
                ref="upload"
                :show-upload-list="false"
                :on-success="handleSuccess"
                :format="format"
                :accept="accept"
                :max-size="maxSize"
                :max-count="maxCount"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                :before-upload="handleBeforeUpload"
                :on-exceeded-count="handleMaxCount"
                multiple
                type="drag"
                :action="action"
                :data="formData"
                style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
                <Icon type="camera" size="20"></Icon>
            </div>
        </MyUpload>
        <Modal title="查看图片" v-model="visible">
            <img :src="imgSrc" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>
<script>
    import MyUpload from './MyUpload.vue';
    import * as api from './../../config';

    export default {
        components: { MyUpload },
        props: {
            action: {
                type: String,
                required: true
            },
            headers: {
                type: Object,
                default () {
                    return {};
                }
            },
            multiple: {
                type: Boolean,
                default: false
            },
            data: {
                type: Object
            },
            name: {
                type: String,
                default: 'file'
            },
            withCredentials: {
                type: Boolean,
                default: false
            },
            format: {
                type: Array,
                default () {
                    return [];
                }
            },
            accept: {
                type: String
            },
            maxSize: {
                type: Number
            },
            maxCount: {
                type: Number
            },
            beforeUpload: Function,
            onProgress: {
                type: Function,
                default () {
                    return {};
                }
            },
            onSuccess: {
                type: Function,
                default () {
                    return {};
                }
            },
            onError: {
                type: Function,
                default () {
                    return {};
                }
            },
            onRemove: {
                type: Function,
                default () {
                    return {};
                }
            },
            onPreview: {
                type: Function,
                default () {
                    return {};
                }
            },
            onExceededSize: {
                type: Function,
                default () {
                    return {};
                }
            },
            onFormatError: {
                type: Function,
                default () {
                    return {};
                }
            },
            value: {
                type: Array,
                default: function() {
                    return [];
                }
            },
            shopId: {
                type:Number,
                default:0
            },
        },
        data () {
            return {
                imgSrc: '',
                visible: false,
                uploadList: [],
                formData: {token:''},
            }
        },
        mounted () {
            this.uploadList = this.$refs.upload.fileList;
        },
        methods: {
            handleView (item) {
                this.imgSrc = item.url;
                this.visible = true;
            },
            handleRemove (file) {
                // 从 upload 实例删除数据
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
                this.emitValue(this.uploadList);
            },
            handleSuccess (response, file, fileList) {
                file.url = Laravel.qiniuDomain + response.url;
                file.name = response.file_name;
                this.emitValue(this.uploadList);
            },
            handleFormatError (file) {
                this.$Notice.warning({
                    title: '文件格式不正确',
                    desc: '文件 ' + file.name + ' 格式不正确，请上传jpg,png,gif格式的图片。'
                });
            },
            handleMaxSize (file, fileList) {
                this.$Notice.warning({
                    title: '超出文件大小限制',
                    desc: '文件 ' + file.name + ' 太大，不能超过 ' + this.maxSize + 'KB'
                });
            },
            handleMaxCount (files) {
                this.$Notice.warning({
                    title: '最多只能上传 ' + this.maxCount + ' 张图片。'
                });
            },
            handleBeforeUpload (file) {
                if (!this.formData.token) {
                    return axios.get(api.getQiniuToken)
                        .then(response => {
                            this.formData.token = response.data.token;
                            this.formData['x:shop_id'] = this.shopId;
                            this.beforeUpload(file);
                        })
                        .catch(error => {

                        });
                }
            },
            emitValue(uploadList) {
                const imgs = [];
                for(let i in uploadList) {
                    if(uploadList[i].response) {
                        imgs.push({img:uploadList[i].response.url, url:uploadList[i].url});
                    } else if(uploadList[i].init) {
                        imgs.push({img:uploadList[i].img, url:uploadList[i].url});
                    }
                }
                this.$emit('input', imgs);
            },
            initFileList(imgs) {
                this.$refs.upload.fileList = imgs.map(item => {
                    item.status = 'finished';
                    item.init = true;
                    item.percentage = 100;
                    item.uid = Date.now() + this.tempIndex++;
                    this.uploadList.push();
                    return item;
                });
                this.uploadList = this.$refs.upload.fileList;
            }
        }
    }
</script>