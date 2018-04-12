<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="ios-gear-outline"></Icon>修改密码</p>
        </div>
        <Form class="inner-form" ref="userData" :model="userData" :rules="ruleValidate" :label-width="80">
            <Form-item label="旧密码" prop="password_old">
                <Input placeholder="旧密码" v-model="userData.password_old" type="password"></Input>
            </Form-item>
            <Form-item label="新密码" prop="password">
                <Input placeholder="新密码" v-model="userData.password" type="password"></Input>
            </Form-item>
            <Form-item label="确认密码" prop="password_confirmation">
                <Input placeholder="确认密码" v-model="userData.password_confirmation" type="password"></Input>
            </Form-item>
            <Form-item>
                <Button type="primary" :loading="loading" @click="handleSubmit('userData')">
                    <span v-if="!loading">保存</span>
                    <span v-else>保存中...</span>
                </Button>
            </Form-item>
        </Form>
    </div>
</template>
<script>
    import {mapState} from 'vuex';
    import RegionPicker from '../../components/common/RegionPicker.vue';
    import SingleImageUpload from '../../components/common/SingleImageUpload.vue';

    export default {
        data() {
            return {
                loading: false,
                label: '',
                ruleValidate: {
                    password_old: [
                        { required: true, message: '旧密码不能为空', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: '新密码不能为空', trigger: 'blur' }
                    ],
                    password_confirmation: [
                        { required: true, message: '确认密码不能为空', trigger: 'blur' }
                    ],
                },
                userData:{
                    password_old:''
                }
            }
        },
        created() {},
        mounted () {},

        methods:{
            handleSubmit(name){
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        var data = {
                            'password_old': this.userData.password_old,
                            'password': this.userData.password,
                            'password_confirmation': this.userData.password_confirmation,
                        };
                        this.$store.dispatch('editPasswordRequest', data)
                            .then((response) => {
                                this.loading = false;
                                this.$Message.success('保存成功');
                            })
                            .catch((error) => {
                                this.loading = false;
                            });
                    } else {
                        this.loading = false;
                    }
                });
            }
        }
    }
</script>