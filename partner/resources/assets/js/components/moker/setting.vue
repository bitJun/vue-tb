<template>
  <div class="moker_setting">
    <h4 class="title">魔客设置</h4>
    <Form class="setting" :model="formItem" :label-width="80">
      <p class="tli">等级设置</p>
      <FormItem>
        <Row>
          <Col span="2">普通魔客</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>元
          </Col>
          <Col span="2">最多添加</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>个商家
          </Col>
        </Row>
      </FormItem>
      <FormItem>
        <Row>
          <Col span="2">普通魔客</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>元
          </Col>
          <Col span="2">最多添加</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>个商家
          </Col>
        </Row>
      </FormItem>
      <FormItem>
        <Row>
          <Col span="2">普通魔客</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>元
          </Col>
          <Col span="2">最多添加</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>个商家
          </Col>
        </Row>
      </FormItem>
      <p class="tli">邀约下级魔客奖金设置</p>
      <FormItem>
        <Row>
          <Col span="4">邀请一个普通魔客奖励</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>元
          </Col>
        </Row>
      </FormItem>
      <FormItem>
        <Row>
          <Col span="4">邀请一个普通魔客奖励</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>元
          </Col>
        </Row>
      </FormItem>
      <FormItem>
        <Row>
          <Col span="4">邀请一个普通魔客奖励</Col>
          <Col span="6">
            <Input v-model="formItem.input" placeholder="Enter something..." style="width:80%;margin-right:5px"></Input>元
          </Col>
        </Row>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="submit()">保存</Button>
        <span class="remark">注：超级管理员权限</span>
      </FormItem>
    </Form>
  </div>
</template>
<script>
export default {
  name: 'moker_setting',
  data () {
    return {
      formItem: {
        input: '',
        select: '',
        radio: 'male',
        checkbox: [],
        switch: true,
        date: '',
        time: '',
        slider: [20, 50],
        textarea: ''
      }
    }
  },
  methods: {
    submit() {

    }
  }
}
</script>
