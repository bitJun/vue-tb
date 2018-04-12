<template>
    <div class="container">
        <div class="inner-header">
            <p class="page-title"><Icon type="ios-gear-outline"></Icon>商户设置</p>
        </div>
        <Form class="inner-form" ref="shopData" :model="shopData" :rules="ruleValidate" :label-width="80">
            <Form-item label="商户名称" prop="name">
                <span>{{shopData.name}}</span>
            </Form-item>
            <Form-item label="联系电话" prop="tel">
                <Input placeholder="联系电话" v-model="shopData.tel" ></Input>
            </Form-item>
            <Form-item label="商户LOGO" prop="logo">
                <SingleImageUpload ref="imgupload"
                        :action="uploadAction" v-model="shopData.logo">
                </SingleImageUpload>
            </Form-item>
            <Form-item label="商户地址" prop="address">
                <RegionPicker :regionData="regions" v-model="shopData.selectedRegion"></RegionPicker>
                <Input style="margin-top:10px" placeholder="详细地址" v-model="shopData.address" ></Input>
            </Form-item>
            <Form-item>
                <Button type="primary" :loading="loading" @click="handleSubmit('shopData')">
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
                uploadAction: Laravel.uploadAction,
                loading: false,
                label: '',
                ruleValidate: {
                    tel: [
                        { required: true, message: '联系电话不能为空', trigger: 'blur' }
                    ],
                    address: [
                        { required: true, message: '商户地址不能为空', trigger: 'blur' }
                    ],
                },
                shopData:{
                    id:0,
                    name:'',
                    logo: {url:'', img:''},
                    province:'',
                    city:'',
                    district:'',
                    address:''

                }
            }
        },
        components: {
            RegionPicker,
            SingleImageUpload
        },
        computed: {
            ...mapState({
                regions: state => state.region.regions,
            }),
        },
        created() {
            this.$store.dispatch('getRegions');

            this.$store.dispatch('getShopRequest').then(response => {
                this.shopData = response;
                this.shopData.selectedRegion = [this.shopData.province,this.shopData.city,this.shopData.district];
                this.shopData.logo = {'url':this.shopData.logo_url,'img':this.shopData.logo};
                this.$refs.imgupload.initImg(response.logo);
            });

        },
        mounted () {
            //this.getRegions();
        },

        methods:{
            handleSubmit(name){
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if(valid) {
                        var data = {
                            'tel': this.shopData.tel,
                            'province': this.shopData.selectedRegion[0].id,
                            'city': this.shopData.selectedRegion[1].id,
                            'district': this.shopData.selectedRegion[2].id,
                            'address': this.shopData.address,
                            'logo': this.shopData.logo,
                        };
                        this.$store.dispatch('editShopRequest', data)
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