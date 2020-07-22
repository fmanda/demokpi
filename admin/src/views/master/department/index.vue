<template>
  <div class="app-container">
    <el-table
      :v-loading="listLoading"
      :data="data.filter(data => !search || data.deptname.toLowerCase().includes(search.toLowerCase()))"
      style="width: 100%"
    >
      <el-table-column label="Dept Code" prop="deptcode" width="100px" />
      <el-table-column label="Dept Name" prop="deptname" />
      <el-table-column
        align="right"
      >
        <template slot="header" slot-scope="scope">
          <el-input
            v-model="search"
            size="mini"
            placeholder="Type to search"
          />
        </template>
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)"
          >Edit</el-button>
          <!-- <el-button
            v-if="scope.row.deptcode !== null "
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)"
          >Delete</el-button> -->
        </template>
      </el-table-column>
    </el-table>
    <br>
    <el-button type="success" icon="el-icon-plus" @click.native.prevent="handleNew()">Add Department</el-button>
  </div>
</template>

<script>
import { getListDept } from '@/api/department'

export default {
  data() {
    return {
      data: [],
      listLoading: true,
      search: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getListDept().then(response => {
        this.data = response.data;
        this.listLoading = false
      })
    },
    handleEdit(index, row) {
      this.$router.push({ name: 'update_department', params: { id: row.id }})
      // this.$router.push({path: '/master/update_department', params: {id: row.id} })
    },
    handleNew() {
      this.$router.push({ path: '/master/update_department' })
    }
  }
}
</script>

<style scoped>
  .el-table >>> .cell {
    word-break: break-word;
    white-space: pre-wrap;
  }

  .el-table >>> thead {
    color: rgb(191, 203, 217);
    font-weight: 500;
    background: #304156;
  }

  .el-table >>> th {
    /* color: rgb(191, 203, 217); */
    background: #304156;
  }
</style>
