<template>
  <div class="app-container">
    <el-table
      :v-loading="listLoading"
      :data="data.filter(data => !search || data.username.toLowerCase().includes(search.toLowerCase()))"
      style="width: 100%"
    >
      <el-table-column label="User Name" prop="username"></el-table-column>
      <el-table-column label="Full Name" prop="fullname"></el-table-column>
      <el-table-column label="Dept Code" prop="deptcode"></el-table-column>
      <el-table-column label="Dept Name" prop="deptname"></el-table-column>
      <el-table-column
        align="right">
        <template slot="header" slot-scope="scope">
          <el-input
            v-model="search"
            size="mini"
            placeholder="Type to search"/>
        </template>
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { getUsers } from '@/api/users'

export default {
  data() {
    return {
      data: null,
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
      getUsers().then(response => {
        this.data = response.data;
        this.listLoading = false
      })
    },
    handleEdit(index, row) {
      console.log(index, row);
    },
    handleDelete(index, row) {
      console.log(index, row);
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
    color: rgb(191, 203, 217);
    background: #304156;
  }
</style>
