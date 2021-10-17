<template>
  <div >
    <Header></Header>
    <div class="filter">
      <div style="display: inline-block">
        <el-date-picker
          v-model="query.start_time"
          type="date"
          format="dd/MM/yyyy"
          value-format="yyyy-MM-dd"
          placeholder="Ngày bắt đầu">
        </el-date-picker>
      </div>
      <div style="display: inline-block">
        <el-date-picker
          v-model="query.end_time"
          type="date"
          format="dd/MM/yyyy"
          value-format="yyyy-MM-dd"
          placeholder="Ngày kết thúc">
        </el-date-picker>
      </div>
      <div style="display: inline-block">
        <el-input :clearable="true" v-model="query.name" placeholder="Nhập họ tên"></el-input>
      </div>
      <div style="display: inline-block">
        <el-input :clearable="true" v-model="query.email" placeholder="Email"></el-input>
      </div>
      <div style="display: inline-block">
        <el-input :clearable="true" v-model="query.address" placeholder="Địa chỉ"></el-input>
      </div>
      <div style="display: inline-block">
        <el-input :clearable="true" v-model="query.mobile" placeholder="Số điện thoại"></el-input>
      </div>
      <div style="display: inline-block">
        <el-input :clearable="true" v-model="query.code_tag" placeholder="Mã thẻ"></el-input>
      </div>
      <div style="display: inline-block">
        <el-select
          :clearable="true"
          v-model="query.payment_status"
          placeholder="Trạng thái thanh toán">
          <el-option label="Đã thanh toán" :value="1"></el-option>
          <el-option label="Chưa thanh toán" :value="0"></el-option>
        </el-select>
      </div>
      <div style="display: inline-block; margin-top: 5px">
        <el-button @click="handleFilter" type="primary"> Tìm kiếm</el-button>
      </div>
    </div>
    <div class="content">
      <el-table
        :data="listOrder"
        empty-text="loading"
        border
        style="width: 100%">
        <el-table-column
          min-width="200px"
          label="Thông tin">
          <template slot-scope="scope">
            <div>-Tên: {{scope.row.name}}</div>
            <div>-Email: {{scope.row.email}}</div>
            <div>-Địa chỉ: {{scope.row.address}}</div>
            <div>-Sđt: {{scope.row.mobile}}</div>
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          label="Mã thẻ">
          <template slot-scope="scope">
            {{scope.row.code_tag}}
          </template>
        </el-table-column>
        <el-table-column
          width="250px"
          align="center"
          label="Đường link">
          <template slot-scope="scope">
            https://info.vicard.vn/view/{{scope.row.code_tag}}
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          label="Email đơn hàng">
          <template slot-scope="scope">
            <el-tag :type="scope.row.send_email_order ? 'primary':'info'">{{scope.row.send_email_order ? 'đã gửi':'chưa gửi'}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          label="Trạng thái">
          <template slot-scope="scope">
            <el-tag :type="scope.row.payment_status === 0 ? 'info':'success'">{{scope.row.payment_status === 0 ? 'chưa thanh toán':'đã thanh toán'}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          width="150px"
          align-item="center"
          label="Ngày đăng ký">
          <template slot-scope="scope">
             {{scope.row.created_at | formatDate}}
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          label="Xác nhận">
          <template slot-scope="scope">
            <el-button size="small" type="primary">Xác nhận</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-pagination
        v-if="query.total > query.limit"
        background
        layout="prev, pager, next"
        @current-change="changePage"
        :page-size="query.limit"
        :total="query.total">
      </el-pagination>
    </div>
    <Footer></Footer>
  </div>
</template>

<script>
  import Header from '../components/layout/header'
  import Steps from '../components/layout/steps'
  import Footer from '../components/layout/footer'

  import moment from 'moment';

  export default {
    components:{
      Header,
      Steps,
      Footer
    },
    data() {
      return {
        listOrder: [],
        query: {
          page: 1,
          limit: 20,
          total: 0,
          name: '',
          email: '',
          mobile: '',
          address: '',
          code_tag: '',
          payment_status: '',
          start_time: '',
          end_time: ''
        }
      }
    },
    filters: {
      formatDate(value) {
        if(value) {
          return moment(value).format('YYYY-MM-DD HH:mm')
        }
      }
    },
    created() {
      this.list();
    },
    methods: {
      list() {
        const params = this.query;
        axios.get('order', { params }).then((res) => {
          this.listOrder = res.data.data.data;
          this.query.total = res.data.data.total;
        }).catch((error) => {
          this.$notify({
            message: error.response.data.message,
            type: 'error'
          });
        });
      },
      handleFilter() {
        this.query.page = 1;
        this.list();
      },
      changePage(page) {
        this.query.page = page;
        this.list();
      }
    }
  }
</script>


<style lang="scss">
  .filter {
    padding: 20px;
  }
  .content {
    padding: 0 20px;
  }
</style>
