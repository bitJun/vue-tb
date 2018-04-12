<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="ios-gear-outline"></Icon>合伙人设置</p>
        </div>
        <Form class="inner-form" ref="levelSettingData" :model="levelSettingData" :rules="ruleValidate" :label-width="160">

            <Form-item label="合伙人启用">
                <i-switch v-model="levelSettingData.value">
                    <span slot="open">是</span>
                    <span slot="close">否</span>
                </i-switch>
            </Form-item>

            <Form-item label="自定义名称">
                <Input v-model="levelSettingData.name" placeholder="如合伙人，不填写默认股东合伙人"></Input>
            </Form-item>

            <Form-item label="等级条件" prop="credit_limit">
                <Input v-model="levelSettingData.credit_limit" placeholder="成为合伙人需获得的魔豆数量"></Input>
            </Form-item>
            <Form-item label="保留等级的条件" prop="keep_limit">
                <Input v-model="levelSettingData.keep_limit" placeholder="保留合伙人等级需获得的魔豆数量"></Input>

                <!--<Alert style="margin-top: 15px;" type="warning" show-icon>
                    <template slot="desc">
                        修改等级保留条件，将重新升级合伙人，请谨慎修改！
                    </template>
                </Alert>-->

            </Form-item>




            <Form-item>
                <Button type="primary" :loading="loading" @click="handleSubmit('levelSettingData')">
                    <span v-if="!loading">保存</span>
                    <span v-else>保存中...</span>
                </Button>
            </Form-item>
        </Form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                loading:false,
                ruleValidate: {
                    credit_limit: [
                        { required: true, pattern: /^[0-9]+?$/, message: '必须是大于0的数字', trigger: 'blur' },
                    ],
                    keep_limit: [
                        { pattern: /^[0-9]+$/, message: '必须是大于0的数字', trigger: 'blur' },
                    ],
                },
                levelSettingData:{
                    //'value':true
                }
            }
        },
        created() {
            this.$store.dispatch('getMemberLevelRequest').then(response => {
                    this.levelSettingData = response;
                    this.levelSettingData.value = response.enabled ? true : false;
                }
            );
        },
        methods:{
            handleSubmit(name){
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        /*var data = {
                            'self_percent': this.creditRuleData.self_percent,
                            'parent_percent': this.creditRuleData.parent_percent,
                            'partner_percent': this.creditRuleData.partner_percent
                        };*/
                        this.$store.dispatch('editMemberLevelRequest', this.levelSettingData)
                            .then((response) => {
                                this.loading = false;
                                this.$Message.success('设置成功');
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