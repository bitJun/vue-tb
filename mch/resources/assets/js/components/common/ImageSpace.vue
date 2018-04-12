<template>
    <div class="modal-img-space">
        <div class="modal-header">
            <button type="button" class="close" @click="cancel()" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
            <div class="clearfix">
                <div class="pull-left">
                    <div class="fileinput-button js-upspace-btn btn btn-sm btn-primary">
                        <span class="ng-binding"><span><i class="fa fa-plus ng-scope"></i></span>{{ uploadText }}</span>
                        <input type="file" name="file" multiple="" @click="upload">
                    </div>
                </div>
                <div class="input-group input-group-sm ml20 pull-left remote-url-box">
                    <input type="text" placeholder="请贴入网络图片地址"  ng-model="remote" name="remote_url" class="form-control input-sm" value="">
                    <span class="input-group-btn mr10"><button remote-image remote="remote" upimages="upimages" class="btn btn-default btn-sm">提取</button></span>
                </div>
                <div class="input-group input-group-sm ml20 pull-left search-box">
                    <input type="text" placeholder="输入文件名" class="form-control" v-model="searchParam.title" />
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button" @click="search()">搜索</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <ul class="image-list clearfix">
                <li @click="choose(item)" v-for="item in images">
                    <div class="image-img">
                        <img :src="item.url" />
                        <div class="image-meta">{{item.imageinfo.width}}x{{item.imageinfo.height}}</div>
                    </div>
                    <div v-if="item.file_name_without_ext.length > 5" class="image-name" :title="item.file_name_without_ext">{{item.file_name_without_ext.substring(0,5)}}...</div>
                    <div v-if="item.file_name_without_ext.length <= 5" class="image-name" :title="item.file_name_without_ext">{{item.file_name_without_ext.substring(0,5)}}</div>
                </li>
                <div v-if="images.length==0" class="well well-lg text-center">
                    <i class="fa fa-file-o"></i> 暂无图片
                </div>
            </ul>
        </div>
        <div class="modal-footer clearfix">
            <!--<Pagination></Pagination>-->
            <button @click="ok()" type="button" class="btn btn-success btn-sm">确定</button>
        </div>
    </div>

</template>
<script>
    import Pagination from '../../components/common/Pagination.vue';
    import Message from 'iview/src/components/message';

    export default {
        components: {
            Pagination,
            Message
        },
        props: {

        },
        data() {
            return {
                uploadText: '上传图片',
                images: [],
                searchParam: {title:''}
            }
        },
        beforeUpdate() {

        },
        computed: {

        },
        methods: {
            upload: function(event) {
                this.uploadText = '正在上传';
                console.log(event);
                const target = event.target;
                console.log(event.target.files);
                for (let i = 0; i < target.files.length; i++) {
                    console.log(target.files[i].name);
                    if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(target.files[i].name)) {
                        this.$Message.error("图片类型必须是.gif,jpeg,jpg,png中的一种");
                        this.uploadText = '上传图片';
                        return false;
                    }
                    if(target.files[i].size > 1024*1024*2)
                    {
                        this.$Message.error("最大上传图片为2M");
                        this.uploadText = '上传图片';
                        return false;
                    }
                }
            }
        }
    }
</script>