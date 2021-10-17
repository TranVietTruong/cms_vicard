<template>
  <div class="upload">
    <el-upload
      ref="upload"
      :data="data"
      action="https://api.cloudinary.com/v1_1/truongtv/upload"
      accept="image/jpeg, image/png"
      :multiple="false"
      :show-file-list="false"
      :auto-upload="autoUpload"
      :on-change="handleChange"
      :before-upload="handleBeforeUpload"
      :on-progress="handleProgress"
      :on-success="handleSuccess"
      :on-remove="handleRemove"
      :on-error="handleError">
      <div class="image_upload">
        <el-avatar :size="120" :src="file"></el-avatar>
        <i class="el-icon-camera icon_upload"></i>
      </div>
      <el-progress v-show="showProgress" :percentage="progress" status="success"></el-progress>
    </el-upload>

    <el-dialog
      :visible.sync="visibleCropImage"
      center>
      <div class="crop_image">
        <VueCropper
          ref="cropper"
          :src="fileCrop"
          dragMode="move"
          :zoomable="false"
          :zoomOnTouch="false"
          :zoomOnWheel="false"
          :movable="false"
          :mode="1"
          :data="{width:50,height:50}"
          :restore="false"
          :guides="false"
          :highlight="true"
          :autoCropArea="1"
          :aspectRatio="1"
          :toggleDragModeOnDblclick="false"
          >
        </VueCropper>
      </div>
      <div class="action">
        <el-button :loading="loadingCrop" @click="cropImage" size="mini" type="primary">Chọn</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';

export default {
  components: { VueCropper},
  props: {
    value: {
      type: Object,
      default: {}
    },
    keyName: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      data: {
        upload_preset: 'j5aj4rg6'
      },
      showfileList: true,
      autoUpload: false,
      progress: 0,
      showProgress: false,
      visibleCropImage:false,
      fileCrop: '',
      imageCrop: '',
      fileList: [],
      uuid: '',
      uploaded: false,
      loadingCrop: false
    }
  },
  mounted() {

  },
  computed: {
    file: {
      get: function() {
        if(this.value && this.value.url) {
          return this.value.url;
        }
        else {
          return '';
        }
      },
      set: function(value) {
        this.value.url = value
      }

    },
  },
  methods: {
    dataURLtoFile(dataurl, filename) {
      var arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);

      while(n--){
        u8arr[n] = bstr.charCodeAt(n);
      }
      return new File([u8arr], filename, {type:mime});
    },
    cropImage() {
      this.loadingCrop = true;
      this.imageCrop = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.file = this.imageCrop;
      let fileCrop = this.dataURLtoFile(this.imageCrop, 'image_crop');
      this.fileList = [{
        name: "image_crop",
        percentage: 0,
        raw: fileCrop,
        size: fileCrop.size,
        status: "ready",
        uid: this.uuid,
      }];
      this.$refs.upload.uploadFiles = this.fileList;
      this.$refs.upload.submit();
      this.visibleCropImage = false;
    },
    handleChange(file) {
      this.visibleCropImage = false;
      this.uuid = file.uuid
      const reader = new FileReader();
      reader.onload = (event) => {
        this.fileCrop = event.target.result;
        this.$refs.cropper.replace(event.target.result);
      };
      reader.readAsDataURL(file.raw);
      if(!this.uploaded)
        this.visibleCropImage = true;
    },
    handleBeforeUpload(file) {
      this.progress = 0;
      this.showProgress = true;
      this.uploaded = true;
      const type = ['image/jpeg', 'image/png'];
      if(!type.includes(file.type)) {
        this.$message.error('Ảnh tải lên không hợp lệ');
        return false;
      }
      return true;
    },
    handleSuccess(file) {
      let data = {
        type: 'IMAGE',
        key: this.keyName,
        info: file.url
      };
      axios.post('create-user-info', data).then((res) => {
        this.$notify({
          message: 'Tải ảnh thành công',
          type: 'success'
        });
        let data = {
          uid: res.data.data.id,
          name: res.data.data.key === 'avatar' ? 'Ảnh avatar': 'Ảnh background',
          url: res.data.data.info
        };
        this.showProgress = false;
        this.$emit('input', data);
        this.uploaded = false;
        this.loadingCrop = false;
      });
    },
    handleProgress(event) {
      this.progress = event.percent;
    },
    handleRemove(file) {
      axios.post('delete-user-info', {id:file.uid}).then((res) => {
        this.$notify({
          message: 'Xóa ảnh thành công',
          type: 'success'
        });
        this.$emit('input',{})
      });
    },
    handleError() {
      this.showProgress = false;
      this.$message.error('Có lỗi xảy ra ! Vui lòng thử lại sau');
    }
  }
}
</script>

<style lang="scss">
  .upload {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;

    .image_upload {
      position: relative;
      .icon_upload {
        font-size: 30px;
        position: absolute;
        bottom: 0;
        right: 1%;
        cursor: pointer;
        background: #f8fafc;
        padding: 5px;
        border-radius: 50%;
      }
    }
    .action {
      margin-top: 10px;
      display: flex;
      justify-content: flex-end;
    }
  }



  .cropper-crop-box, .cropper-view-box {
    border-radius: 50%;
  }
  .cropper-view-box {
    box-shadow: 0 0 0 1px #39f;
    outline: 0;
  }
  .cropper-face {
    background-color:inherit !important;
  }

  .cropper-dashed, .cropper-line {
    display:none !important;
  }
  .cropper-view-box {
    outline:inherit !important;
  }

  .cropper-point.point-se {
    top: calc(85% + 1px);
    right: 14%;
  }
  .cropper-point.point-sw {
    top: calc(85% + 1px);
    left: 14%;
  }
  .cropper-point.point-nw {
    top: calc(15% - 5px);
    left: 14%;
  }
  .cropper-point.point-ne {
    top: calc(15% - 5px);
    right: 14%;
  }
  .cropper-crop-box, .cropper-view-box {
    border-radius: 50%;
  }

  .cropper-view-box {
    box-shadow: 0 0 0 1px #39f;
    outline: 0;
  }

  .cropper-face {
    background-color:inherit !important;
  }

  .cropper-dashed, .cropper-point.point-se, .cropper-point.point-sw, .cropper-point.point-nw,   .cropper-point.point-ne, .cropper-line {
    display:none !important;
  }

  .cropper-view-box {
    outline:inherit !important;
  }
</style>
