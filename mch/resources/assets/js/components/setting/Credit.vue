<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="ios-gear-outline"></Icon>魔豆设置</p>
        </div>
        <Form class="inner-form" ref="creditRuleData" :model="creditRuleData" :rules="ruleValidate" :label-width="160">
            <Form-item label="消费获得魔豆比例" prop="self_percent">
                <Input v-model="creditRuleData.self_percent">
                    <span slot="append">%</span>
                </Input>
            </Form-item>
            <Form-item label="消费上级获得魔豆比例" prop="parent_percent">
                <Input v-model="creditRuleData.parent_percent">
                    <span slot="append">%</span>
                </Input>
            </Form-item>
            <Form-item label="股东合伙人获得魔豆比例" prop="partner_percent">
                <Input v-model="creditRuleData.partner_percent">
                    <span slot="append">%</span>
                </Input>
            </Form-item>
            <Form-item>
                <Button type="primary" :loading="loading" @click="handleSubmit('creditRuleData')">
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
                    self_percent: [
                        { required: true, pattern: /^[0-9]+(\.[0-9]{1,2})?$/, message: '必须是大于0的数字(小数点后2位)', trigger: 'blur' },
                    ],
                    parent_percent: [
                        { required: true, pattern: /^[0-9]+(\.[0-9]{1,2})?$/, message: '必须是大于0的数字(小数点后2位)', trigger: 'blur' },
                    ],
                    partner_percent: [
                        { required: true, pattern: /^[0-9]+(\.[0-9]{1,2})?$/, message: '必须是大于0的数字(小数点后2位)', trigger: 'blur' },
                    ],
                },
                creditRuleData:{

                }
            }
        },
        created() {
            this.$store.dispatch('getCreditRuleRequest').then(response => {
                    this.creditRuleData = response.rule;
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
                        this.$store.dispatch('editCreditRuleRequest', this.creditRuleData)
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