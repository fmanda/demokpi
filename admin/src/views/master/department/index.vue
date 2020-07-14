<template>
  <div class="app-container">
    <el-form ref="department" :model="department" label-width="120px" label-position="top">
      <el-form-item label="Department">
        <el-col :span="2">
          <el-input v-model="department.deptcode" placeholder="Dept Code" style="width: 90%;" />
        </el-col>
        <el-col :span="5">
          <el-input v-model="department.deptname" placeholder="Department Name" />
        </el-col>
      </el-form-item>

      <el-form-item label="Maturity Level">
        <el-table :data="department.mlitems" style="width: 100%">
          <el-table-column prop="areacode" label="Area Code" width="120">
            <template slot-scope="scope">
              <el-input v-model="scope.row.areacode" placeholder="Area Code" />
            </template>
          </el-table-column>
          <el-table-column prop="areacode" label="Area Name" width="300">
            <template slot-scope="scope">
              <el-input v-model="scope.row.areaname" placeholder="Area Name" />
            </template>
          </el-table-column>
          <el-table-column label="Operations">
            <template slot-scope="scope">
              <!-- <el-button plain type="primary" size="small" icon="el-icon-plus" @click.native.prevent="deleteRow(scope.$index, scope.row)" /> -->
              <el-button plain type="danger" size="small" icon="el-icon-delete" @click.native.prevent="deleteRow(scope.$index, department.mlitems)" />
              <el-button plain size="small" icon="el-icon-top" @click.native.prevent="moveRow(scope.$index, department.mlitems,1) "/>
              <el-button plain size="small" icon="el-icon-bottom" @click.native.prevent="moveRow(scope.$index, department.mlitems,-1) "/>
            </template>
          </el-table-column>
        </el-table>
      </el-form-item>
      <el-button plain type="primary" icon="el-icon-plus" @click.native.prevent="addRow(department.mlitems)" >Add Maturity Area</el-button>

    </el-form>

  </div>
</template>

<script>
import { test } from '@/api/test'

export default {
  data() {
    return {
      editing: null,
      department: {
        id: 0,
        deptcode: '',
        deptname: '',
        mlitems: [{
          areacode: 'test'
        }],
        kpiitems: []
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      test().then(response => {
        this.test = response.data
        this.loading = false
      })
    },
    deleteRow(index, items) {
      items.splice(index, 1)
    },
    addRow(items) {
      items.push({ areacode: '', areaname: '' })
    },
    moveRow(index, items, inc) {
      var newindex = index + inc
      console.log(newindex)
      if (newindex < 0) return
      if (newindex >= items.length) return
      var tmp = items[index]
      items[index] = items[newindex]
      items[newindex] = tmp
    }
  }
}
</script>
