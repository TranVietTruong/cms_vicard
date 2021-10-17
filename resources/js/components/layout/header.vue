<template>
  <div class="header">
    <div class="container">
      <el-row type="flex" justify="space-between">
        <div class="header_logo">
          <a href="/" style="text-decoration: none">
            <h1>VICARD</h1>
          </a>
        </div>
        <div class="header_user">
          <el-popover
            placement="bottom"
            width="200"
            trigger="click"
            v-model="visible">
            <div class="header_user_menu">
              <div @click="dangxuat" class="header_user_menu_item">Đăng xuất</div>
            </div>
            <div class="header_user_info" slot="reference">
              <span class="header_user_name">{{email}}</span>
              <el-avatar icon="el-icon-user-solid"></el-avatar>
            </div>
          </el-popover>
        </div>
      </el-row>
    </div>
    <el-dialog title="Đổi mật khẩu" :visible.sync="dialogFormVisible">
      <el-form :model="form" :rules="rules" ref="formChangePassword">
        <el-form-item prop="old_password">
          <p class="login__label">Nhập mật khẩu cũ <span style="color: red;">*</span></p>
          <el-input
            placeholder="Nhập mật khẩu cũ"
            v-model="form.old_password"
            type="password"
            autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item prop="new_password">
          <p class="login__label">Nhập mật khẩu mới <span style="color: red;">*</span></p>
          <el-input
            placeholder="Nhập mật khẩu mới"
            v-model="form.new_password"
            type="password"
            autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button size="mini" @click="dialogFormVisible = false" icon="el-icon-close">Hủy</el-button>
        <el-button size="mini" :loading="loading" type="primary" @click="changePassword" icon="el-icon-save">Lưu</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { mapActions } from 'vuex'
  export default {
    data() {
      return {
        dialogFormVisible: false,
        form: {
          old_password: '',
          new_password: ''
        },
        rules: {
          old_password: [
            { required: true, message: 'Vui lòng nhập mật khẩu cũ', trigger: 'blur' },
            { max: 50, message: 'Mật khẩu cũ không hợp lệ', trigger: 'blur' }
          ],
          new_password: [
            { required: true, message: 'Vui lòng nhập mật khẩu mới', trigger: 'blur' },
            { min:6, message: 'Mật khẩu tối thiểu 6 kí tự', trigger: 'blur' },
            { max:20, message: 'Mật khẩu không hợp lệ', trigger: 'blur' }
          ]
        },
        loading: false,
        visible: false
      }
    },
    computed: {
      email() {
        return this.$store.getters.user && this.$store.getters.user.email
      }
    },
    methods: {
      ...mapActions(['logout']),
      dangxuat() {
        const loading = this.loadingStart();
        this.logout().then(() => {
          loading.close();
          this.$router.push({name:'login'});
        })
      },
      changePassword() {
        this.$refs['formChangePassword'].validate((valid) => {
          if (valid) {
            this.loading = true;
            axios.post('change-password', this.form).then(() =>{
              this.$notify({
                message: 'Thay đổi mật khẩu thành công',
                type: 'success'
              });
              this.form = {
                old_password: '',
                new_password: ''
              };
              this.loading = false;
              this.dialogFormVisible = false;
            }).catch((error) => {
              this.$notify({
                message: error.response.data.message,
                type: 'error'
              });
              this.loading = false;
            });
          }
        });
      }
    }
  }
</script>


<style lang="scss" scoped>
  .header {
    padding: 10px 0;
    background: #303133;
    color: white;
    .header_user {
      .header_user_info {
        display: flex;
        align-items: center;
        cursor: pointer;
        span.header_user_name {
          font-size: 15px;
          margin-right: 10px;
        }
      }
    }
    .header_logo {
      display: flex;
      align-items: center;
      h1 {
        font-family: system-ui;
        color: white;
        font-size: 2rem;
        text-decoration: none;
        font-weight: bold;
      }
    }
  }

  .header_user_menu {
    text-align: center;
    .header_user_menu_item {
      width: 100%;
      padding: 5px;
    }
    .header_user_menu_item:hover {
      color: white;
      background: #909399;
      cursor: pointer;
    }

    .active {
      color: white;
      background: #909399;
      cursor: pointer;
    }
  }
  .login__label {
    margin: 0px;
    font-weight: bold;
  }

  @media only screen and (max-width: 600px) {
    .header_logo {
      h1 {
        font-size: 1.5rem !important;
      }
    }
  }
</style>
