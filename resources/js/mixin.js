export const Mixin = {
  methods:{
    loadingStart() {
      const result = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading'
      });
      return result;
    }
  }
};
