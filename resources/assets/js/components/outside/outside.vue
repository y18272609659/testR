<template>
    <div>
        <el-row type="flex" justify="center">
            <el-col :span="12">
                <div style="text-align:center;">
                    Hello body! sign up or sign in, wake up your lord! <br />
                    嘿朋友！注册或登录，唤醒你的领主吧！ <br />
                    <span style="color: dodgerblue;">如果已经注册，别填注册项。</span>
                </div>
            </el-col>
        </el-row>

        <el-row type="flex" justify="center">
            <el-col :span="10">
                <div>
                    <div style="margin: 20px;"></div>
                    <el-form :model="firstForm" :rules="rules" ref="firstForm" label-width="90px" label-position="right">
                        <el-form-item label="邮箱" prop="email">
                            <el-input v-model="firstForm.email"></el-input>
                        </el-form-item>
                        <el-form-item label="密码" prop="password">
                            <el-input v-model="firstForm.password"></el-input>
                        </el-form-item>
                        <el-form-item label="领主名讳">
                            <el-input v-model="firstForm.nickname"></el-input>
                        </el-form-item>
                        <el-form-item label="王国名称">
                            <el-input v-model="firstForm.kingdom"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <div style="text-align: center;">
                                <el-button type="success" @click="submitForm('firstForm')" round>启程</el-button>
                            </div>
                        </el-form-item>
                    </el-form>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
  import Swal from 'sweetalert2'

  export default {
    name: "Outside",
    data() {
      return {
        params: params,
        firstForm: {
          nickname: '',
          kingdom: '',
          email: '',
          password: '',
        },
        rules: {
          email: [{required: true, message: '登录必填'}],
          password: [{required: true, message: '登录必填'}],
        }
      }
    },
    methods: {
      submitForm: function (formName) {
        this.$refs[formName].validate((valid) => {
          let url = '/login'
          if (this.firstForm.nickname !== '') {
            url = '/register'
          }

          this.$http.post(url, this.firstForm).then(response => {
            response = response.body
            if (response[0] === 101) {
              localStorage.setItem('user', JSON.stringify(response[1]))
              window.location.href = params.mainSite
            } else {
              Swal({
                text: response[1],
                type: 'error',
              })
            }
          }, response => {
            Swal({
              text: '无法完成任务，服务器错误',
              type: 'error',
            })
          })
        });
      },
    },
  }
</script>

<style scoped>

</style>
