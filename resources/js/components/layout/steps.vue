<template>
  <div class="steps">
    <el-menu
      :default-active="active">
      <el-menu-item @click="openListTemplate" index="1">
        <i class="el-icon-menu"></i>
        <span>Chọn mẫu website</span>
      </el-menu-item>
      <el-menu-item @click="openInfo" index="2">
        <i class="el-icon-document"></i>
        <span>Thiết lập thông tin</span>
      </el-menu-item>
    </el-menu>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        active: this.$store.getters.getStep
      }
    },
    created() {
      if(this.$route.name === 'listTemplate') {
        this.active = '1'
      }
      else {
        this.active = '2'
      }
    },
    methods: {
      openListTemplate() {
        if(this.$route.name !== 'listTemplate') {
          this.$router.push({name:'listTemplate'});
        }
      },
      openInfo() {
        if(this.$route.name !== 'info') {
          this.$router.push({name:'info'});
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .el-menu {
     border-right: none;
     background-color: inherit !important;
  }
</style>
