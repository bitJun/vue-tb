<template>
  <div class="setting_password">
    <h4 class="title">修改密码</h4>
    <Form  ref="userData" :model="userData" :rules="ruleValidate"  class="add_form" :label-width="100">
      <FormItem label="旧密码" prop="password_old">
        <Input placeholder="旧密码" v-model="userData.password_old" type="password" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="新密码" prop="password">
        <Input placeholder="新密码" v-model="userData.password" type="password" style="width: 400px"></Input>
      </FormItem>
      <FormItem label="确认密码" prop="password_confirmation">
        <Input placeholder="确认密码" v-model="userData.password_confirmation" type="password" style="width: 400px"></Input>
      </FormItem>
      <FormItem>
        <Button type="primary" :loading="loading" @click="handleSubmit('userData')">
          <span v-if="!loading">保存</span>
          <span v-else>保存中...</span>
        </Button>
      </FormItem>
    </Form>
  </div>
</template>
<script>
export default {
  name: 'setting_password',
  data () {
    return {
      loading: false,
      label: '',
      userData: {
        password_old: '',
        password: '',
        password_confirmation: ''
      },
      ruleValidate: {
        password_old: [
          { required: true, message: '旧密码不能为空', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '新密码不能为空', trigger: 'blur' }
        ],
        password_confirmation: [
          { required: true, message: '确认密码不能为空', trigger: 'blur' }
        ]
      },
    }
  },
  methods: {
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
              this.logout();
            })
            .catch((error) => {
                this.loading = false;
            });
        } else {
          this.loading = false;
        }
      });
    },
    logout() {
      this.$store.dispatch('logoutRequest')
        .then(() => {
          this.$router.push({path: '/login'});
        });
    }
  }
}
</script>
