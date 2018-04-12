<template>
    <Upload
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
            :data="formData">
        <div v-if="!img.url">
            <div style="padding: 20px 0">
                <Icon type="camera" size="52" style="color: #3399ff"></Icon>
                <p>{{ label }}</p>
            </div>
        </div>
        <div @mouseover="toggleShow()" @mouseout="toggleShow()" v-if="img.url" :style="styleObject">
            <div :class="showIconClass" style="padding: 20px 0;">
                <Icon type="camera" size="52" style="color: #fff"></Icon>
                <p style="color: #fff;font-weight:bold">{{ label }}</p>
            </div>
        </div>
    </Upload>
</template>
<script>
    import * as api from './../../config';

    export default {
        props: {
            label: {
                type: String,
                default: '上传照片'
            },
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
                img: {url:'', img:''},
                formData: {token:''},
                showIconClass: 'photo-cover'
            }
        },
        created() {
            this.img = this.value;
        },
        computed: {
            styleObject: function() {
                return {
                    'background': 'url('+this.img.url+') 0% 0% / 100% 100%',
                }
            }
        },
        methods:{
            handleSuccess (response, file, fileList) {
                file.url = Laravel.qiniuDomain + response.url;
                file.img = response.url;
                file.tmp_name = response.tmp_name;
                file.type = response.mime_type;
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
                this.formData['shop_id'] = this.shopId;
            },
            emitValue(img) {
                this.$emit('input', img);
            },
            initImg(img) {
                this.img = img;
            },
            toggleShow: function() {
                if(this.showIconClass == 'photo-cover-hover') {
                    this.showIconClass = 'photo-cover';
                } else if(this.showIconClass == 'photo-cover') {
                    this.showIconClass = 'photo-cover-hover';
                }
            }
        }
    }
</script>
<style>
    .photo-cover{
        visibility: hidden;
    }
    .photo-cover-hover {
        visibility: visible;
        background:rgba(0,0,0,.6);
    }
</style>