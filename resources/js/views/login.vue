<template>
  <div class="container">
    <el-row type="flex" justify="center">
      <el-col :xs="24" :sm="12" :md="12" :lg="8">
        <div class="login">
          <div class="login__header">
            <h3>đăng nhập</h3>
          </div>
          <el-alert
            v-if="error"
            title="Email hoặc mật khẩu không chính xác"
            type="error"
            show-icon>
          </el-alert>
          <el-form :model="form" :rules="rules" status-icon ref="formLogin">
            <p class="login__label">Email <span class="text-danger">*</span></p>
            <el-form-item prop="email">
              <el-input
                placeholder="Nhập email"
                v-model="form.email"></el-input>
            </el-form-item>
            <p class="login__label">Mật khẩu <span class="text-danger">*</span></p>
            <el-form-item prop="password">
              <el-input
                @keyup.enter.native="loginForm"
                placeholder="Nhập mật khẩu"
                type="password"
                v-model="form.password"></el-input>
            </el-form-item>
            <el-form-item>
              <el-row type="flex" justify="center" class="mt-2">
                <el-button @click="loginForm" :loading="loadingLogin" type="primary">Đăng Nhập</el-button>
              </el-row>
            </el-form-item>
          </el-form>
        </div>
      </el-col>
    </el-row>
    <el-dialog class="forget_pass" title="Quên mật khẩu" :visible.sync="dialogFormForgetPassword">
      <el-form :model="formForgetPassword" :rules="rulesForget" ref="formForgetPassword">
        <el-form-item prop="email">
          <p style="margin: 0px; font-weight: bold" class="login__label">
            Nhập email
            <span class="text-danger">*</span>
          </p>
          <el-input v-model="formForgetPassword.email" placeholder="Nhập email" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button size="mini" @click="dialogFormForgetPassword = false" icon="el-icon-close">Hủy</el-button>
        <el-button size="mini" :loading="loadingForget" type="primary" @click="forgetPassword" icon="el-icon-save">Xác nhận</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';
  export default {
    data() {
      return {
        form: {
          email: '',
          password: ''
        },
        loadingLogin: false,
        loadingForget: false,
        error: false,
        rules: {
          email: [
            { required: true, message: 'Vui lòng điền email', trigger: 'blur' },
            { max: 50, message: 'Vui lòng điền email', trigger: 'blur' }
          ],
          password: [
            { required: true, message: 'Vui lòng điền mật khẩu', trigger: 'blur' },
            { min:6, message: 'Mật khẩu tối thiểu 6 kí tự', trigger: 'blur' },
            { max:50, message: 'Mật khẩu không hợp lệ', trigger: 'blur' }
          ]
        },
        rulesForget: {
          email: [
            { required: true, message: 'Vui lòng điền email', trigger: 'blur' },
            { max: 50, message: 'Vui lòng điền email', trigger: 'blur' }
          ]
        },
        formForgetPassword: {
          email: ''
        },
        dialogFormForgetPassword: false
      }
    },
    methods: {
      ...mapActions(['attempt']),
      loginForm() {
        this.$refs['formLogin'].validate((valid) => {
          if (valid) {
            this.loadingLogin = true;
            this.error = false;
            this.attempt(this.form).then(() => {
              this.$router.push({name:'home'})
            })
            .catch(() => {
              this.error = true;
              this.loadingLogin = false;
            })
          }
        });
      },
      forgetPassword()
      {
        this.$refs['formForgetPassword'].validate((valid) => {
          if (valid) {
            this.loadingForget = true;
            axios.post('forget-password', this.formForgetPassword).then((res) => {
              this.$notify({
                message: 'Vui lòng kiểm tra email để lấy lại mật khẩu',
                type: 'success'
              });
              this.loadingForget = false;
            }).catch((error) => {
              this.$notify({
                message: error.response.data.message,
                type: 'error'
              });
              this.loadingForget = false;
            });
          }
        });
      }
    }
  }
</script>

<style lang="scss" scoped>
.login {
  margin-top: 15vh;
  padding: 0 25px;
  .login__label {
    margin: 5px 0px;
    font-weight: bold;
  }
  .login__header {
     h3 {
      font-weight: bold;
    }
    text-align: center;
    text-transform: uppercase;
    padding: 20px;
  }
}
.reserved {
  font-size: 12px;
  padding: 10px;
}
.forget {
  font-weight: bold;
  text-align: end;
}
.forget:hover {
  text-decoration: underline;
  cursor: pointer;
}
.redirect {
  text-align: center;
  font-weight: bold;
}

.end {
  text-align: center;
  margin-top: 20vh;
  margin-bottom: 10vh;
  font-weight: bold;
  font-size: 13px;
}
.end h3 {
  font-size: 20px;
  font-weight: bold;
}
</style>
