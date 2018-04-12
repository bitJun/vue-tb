<template>
    <Form class="inner-form" ref="timelineData" :model="timelineData" :rules="ruleValidate" :label-width="80">
        <Form-item label="内容" prop="content">
            <Input v-model="timelineData.content" type="textarea" :rows="6" placeholder="请输入内容..."></Input>
        </Form-item>
        <Form-item label="图片" prop="image">
            <ImageUpload ref="imgupload"
                :action="uploadAction"
                :format="['jpg','jpeg','png','gif']"
                :max-size="2048"
                :max-count="3"
                :shop-id="shop_id"
                v-model="timelineData.imgs">
            </ImageUpload>
        </Form-item>
        <Form-item label="外链" prop="link">
            <Input v-model="timelineData.link" placeholder="http://"></Input>
        </Form-item>
        <Form-item>
            <Button type="primary" :loading="loading" @click="handleSubmit('timelineData')">
                <span v-if="!loading">保存</span>
                <span v-else>保存中...</span>
            </Button>
        </Form-item>
    </Form>
</template>
<script>
    import {mapState} from 'vuex';
    import ImageUpload from '../../components/common/ImageUpload.vue';

    export default {
        components: {
            ImageUpload
        },
        props:{
            type:{
                type:String,
                default:'add', //add:新增,edit:编辑
            }
        },
        created() {
            if(this.type == 'edit') {
                this.$store.dispatch('getTimelineRequest', this.$route.params.id).then(response => {
                    this.timelineData = response;
                    this.defaultImgs = response.imgs;

                    this.$refs.imgupload.initFileList(response.imgs);
                    /*this.$refs.imgupload.$refs.upload.fileList = response.imgs.map(item => {
                        item.status = 'finished';
                        item.percentage = 100;
                        item.uid = Date.now() + this.tempIndex++;
                        return item;
                    });
                    this.$refs.imgupload.uploadList =  this.$refs.imgupload.$refs.upload.fileList;*/
                });
            }
        },
        computed: {
            ...mapState({
                shop_id: state => state.authUser.shop_id
            }),
        },
        data () {
            return {
                loading: false,
                uploadAction: Laravel.uploadAction,
                ruleValidate: {
                    link: [
                        { type: 'url', message: '外链格式不正确', trigger: 'blur' }
                    ]
                },
                timelineData:{
                    id:0,
                    content:'',
                    imgs:[],
                    link:''
                }
            }
        },
        methods:{
            handleSubmit(name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        console.log(this.timelineData);
                        if(this.type == 'add'){
                            this.$store.dispatch('addTimelineRequest', this.timelineData)
                                .then((response) => {
                                    console.log(response);
                                    this.loading = false;
                                    this.$Message.success('保存成功');
                                    this.$router.push('/timeline/list');
                                })
                                .catch((error) => {
                                    this.loading = false;
                                });
                        }else if(this.type == 'edit') {
                            this.$store.dispatch('editTimelineRequest', this.timelineData)
                                .then((response) => {
                                    this.loading = false;
                                    this.$Message.success('保存成功');
                                    this.$router.push('/timeline/list');
                                })
                                .catch((error) => {
                                    this.loading = false;
                                });
                        }

                    } else {
                        this.loading = false;
                    }
                });
            },
        }
    }
</script>