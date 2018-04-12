<template>
  <div class="business_add">
    <h4 class="title">添加商家</h4>
    <Form  ref="businseeData" :model="businseeData" :rules="ruleValidate"  class="add_form" :label-width="100">
      <FormItem label="商家名称" prop="company">
        <Input v-model="businseeData.company" placeholder="请输入商家名称" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="所属行业" prop="tag_id">
        <Select v-model="businseeData.tag_id" style="width:400px">
          <Option v-for="item in tags" :value="item.id" :key="item.id">{{ item.title }}</Option>
        </Select>
      </FormItem>
      <FormItem label="联系人" prop="contact">
        <Input v-model="businseeData.contact" placeholder="请输入联系人" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="联系电话" prop="mobile">
        <Input v-model="businseeData.mobile" placeholder="请输入联系电话" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="店铺地址">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" v-if="display">
            <span v-text="labelData"></span> <span class="caret"></span>
        </button>
        <RegionPicker v-model="selectedRegion" :regionsId="regionInfo" v-if="!display"></RegionPicker>
      </FormItem>
      <FormItem label="详细地址">
        <Input v-model="businseeData.address" placeholder="请输入详细地址" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="Logo">
        <SingleUpload ref="imgupload" :action="uploadAction" v-model="businseeData.logo"></SingleUpload>
      </FormItem>
      <FormItem label="营业执照">
        <SingleUpload ref="imgupload" :action="uploadAction" v-model="businseeData.num_license"></SingleUpload>
      </FormItem>
      <FormItem label="店铺图片" prop="pwd">
        <UploadList  ref="imgupload"
                :action="uploadAction"
                :format="['jpg','jpeg','png','gif']"
                :max-size="2048"
                :max-count="9"
                v-model="businseeData.imgs"></UploadList>
      </FormItem>
      <FormItem label="店铺介绍" prop="account">
        <Input v-model="businseeData.description" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入店铺介绍" style="width: 400px"></Input>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="handleSubmit('businseeData')">立即添加</Button>
      </FormItem>
    </Form>
  </div>
</template>
<script>
import RegionPicker from '../common/RegionPicker.vue';
import SingleUpload from '../common/singleUpload.vue';
import UploadList from '../common/uploadList.vue';
export default {
  name: 'businessAdd',
  data () {
    return {
      uploadAction: Modian.uploadAction,
      display: true,
      labelData: '',
      regionInfo: {
        province_id: 0,
        city_id: 0
      },
      businseeData: {
        company: '',
        logo: {},
        province: '',
        city: '',
        district: '',
        address: '',
        tag_id: '',
        num_license: {},
        contact: '',
        mobile: '',
        description: '',
        imgs: []    
      },
      tags: [],
      selectedRegion: [],
      ruleValidate: {
        company: [
          { required: true, message: '公司名不能为空', trigger: 'blur' }
        ],
        contact: [
          { required: true, message: '联系人不能为空', trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '手机号不能为空', trigger: 'blur' },
          { type: 'string', message: '请输入正确的手机号', pattern: /^1[345789]\d{9}$/}
        ]
      }
    }
  },
  created () {
    this.$store.dispatch('getTags')
      .then((response) => {
        this.tags = response;
      })
      .catch((error) => {
        this.loading = false;
      });
    this.labelData = '请选择区域';
    this.display = !this.display;
  },
  components: {
    RegionPicker,
    SingleUpload,
    UploadList
  },
  methods: {
    handleSubmit (name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.businseeData.province = this.selectedRegion[0];
          this.businseeData.city = this.selectedRegion[1];
          this.businseeData.district = this.selectedRegion[2];
          this.$store.dispatch('addshopRequest', this.businseeData)
            .then((response) => {
              this.loading = false;
              this.$Message.success('保存成功');
              this.$router.push('/business');
            })
            .catch((error) => {
              this.loading = false;
            });
        } else {
          this.$Message.error('Fail!');
        }
      })
    }
  },
  watch: {
    'businseeData.imgs' (val, oldVal) {
      console.log(val)
    }
  }
}
</script>
