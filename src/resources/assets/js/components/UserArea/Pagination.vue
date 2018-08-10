<template>
   <el-row>
     <el-col :span="24">
       <el-pagination
         v-if="pagination.total > pagination.per_page"
         style="text-align:center;"
         background
         @current-change="handleCurrentChange"
         :current-page="current"
         :page-size="pagination.per_page"
         layout="prev, pager, next"
         :total="pagination.total">
       </el-pagination>
     </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'Pagination',
  /* data () { */
  /*   return { */
  /*     current: 1 */
  /*   } */
  /* }, */
  props: {
    pagination: Object,
    handleCurrentChange: Function
  },
  computed: {
    current: function () {
      return this.$route.query.page ? parseInt(this.$route.query.page) : 1
    }
  }
}
</script>

