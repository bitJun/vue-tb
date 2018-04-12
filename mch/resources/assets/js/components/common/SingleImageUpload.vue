<template>
    <div>
        <div class="demo-upload-list" v-if="img.url">
            <template v-if="img">
                <img :src="img.url">
                <div class="demo-upload-img-cover">
                    <Icon class="left" type="ios-eye-outline" @click.native="handleView(img)"></Icon>
                    <Icon type="camera" size="20" @click.native="handleEdit(img)"></Icon>
                </div>
            </template>
        </div>
        <Upload v-show="!img.url"
                ref="upload"
                :show-upload-list="false"
                :on-success="handleSuccess"
                :format="format"
                :accept="accept"
                :max-size="maxSize"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                :before-upload="handleBeforeUpload"
                type="drag"
                :action="action"
                :data="formData"
                style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
                <Icon type="camera" size="20"></Icon>
            </div>
        </Upload>
        <Modal title="查看图片" v-model="visible">
            <img :src="imgSrc" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>
<script>
    import * as api from './../../config';

    export default {
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
                type: Object,
                default() {
                    return {
                        url:'',
                        img:''
                    }
                }
            },
            shopId: {
                type:Number,
                default:0
            },
        },
        data () {
            return {
                imgSrc:'',
                visible: false,
                img: {url:'', img:''},
                formData: {token:''},
            }
        },
        created() {
            this.img = this.value;
        },
        methods: {
            handleView (file) {
                this.imgSrc = file.url;
                this.visible = true;
            },
            handleEdit () {
                this.$refs.upload.handleClick();
            },
            handleSuccess (response, file, fileList) {
                file.url = Laravel.qiniuDomain + response.url;
                file.img = response.url;
                this.img = file;
                this.emitValue(file);
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
            emitValue(img) {
                this.$emit('input', img);
            },
            initImg(img) {
                this.img = img;
            }
        }
    }
</script>