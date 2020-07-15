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
        <el-col :span="2" style="margin-left: 10px;">
          <el-button type="primary" style="width: 100%;" icon="el-icon-success" @click.native.prevent="saveData()">Save</el-button>
        </el-col>
        <el-col :span="2" style="margin-left: 10px;">
          <el-button type="danger" style="width: 100%;" icon="el-icon-delete">Delete</el-button>
        </el-col>
      </el-form-item>
    </el-form>

    <el-tabs type="border-card">
      <el-tab-pane>
        <span slot="label"><i class="el-icon-s-opportunity" /> Maturity Level</span>
        <el-table :data="department.mlitems" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="props">
              <el-card class="box-card">
                <el-table :data="props.row.items" style="width: 100%">
                  <el-table-column prop="subcode" label="Sub Code" width="120">
                    <template slot-scope="sc">
                      <el-input v-model="sc.row.subcode" placeholder="Sub Code" />
                    </template>
                  </el-table-column>
                  <el-table-column prop="subname" label="Sub Area Name" style="width: 100%">
                    <template slot-scope="sc">
                      <el-input v-model="sc.row.subname" placeholder="Sub Name" />
                    </template>
                  </el-table-column>
                  <el-table-column prop="weight" label="Bobot" width="160">
                    <template slot-scope="sc">
                      <!-- <el-input type="number" step="0.01" v-model="sc.row.weight" placeholder="Weight" /> -->
                      <el-input-number v-model="sc.row.weight" :precision="2" :step="0.1" :max="10" />
                    </template>
                  </el-table-column>
                  <el-table-column label="Operations">
                    <template slot-scope="sc">
                      <el-button plain type="danger" size="small" icon="el-icon-delete" @click.native.prevent="deleteRow(sc.$index, props.row.items)" />
                      <el-button plain size="small" icon="el-icon-top" @click.native.prevent="moveRow(sc.$index, props.row.items,-1) " />
                      <el-button plain size="small" icon="el-icon-bottom" @click.native.prevent="moveRow(sc.$index, props.row.items,1) " />
                    </template>
                  </el-table-column>
                </el-table>
                <br>
                <el-button plain type="primary" icon="el-icon-plus" @click.native.prevent="addSubRow(props.row.items)">Add Sub Area</el-button>
              </el-card>
            </template>
          </el-table-column>
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
              <el-button plain type="danger" size="small" icon="el-icon-delete" @click.native.prevent="deleteRow(scope.$index, department.mlitems)" />
              <el-button plain size="small" icon="el-icon-top" @click.native.prevent="moveRow(scope.$index, department.mlitems,-1) " />
              <el-button plain size="small" icon="el-icon-bottom" @click.native.prevent="moveRow(scope.$index, department.mlitems,1) " />
            </template>
          </el-table-column>
        </el-table>
        <br>
        <el-button type="success" icon="el-icon-plus" @click.native.prevent="addRow(department.mlitems)">Add ML Area</el-button>
      </el-tab-pane>

      <el-tab-pane>
        <span slot="label"><i class="el-icon-s-data" /> KPI Level</span>
      </el-tab-pane>
      <el-tab-pane label="Users">Users</el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
// import { test } from '@/api/test'
import { getDepartment, postDepartment } from '@/api/department'

export default {
  data() {
    return {
      editing: null,
      department: {
        id: 0,
        deptcode: '',
        deptname: '',
        mlitems: [],
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
      getDepartment(4).then(response => {
        this.department = response.data
        this.loading = false
      })
    },
    saveData() {
      this.$confirm('Anda yaking Data Sudah Sesuai?', 'Confirmation', {
        cancelButtonText: 'Cancel',
        confirmButtonText: 'OK',
        type: 'warning'
      }).then(() => {
        this.postData()
      })
    },
    postData() {
      var vm = this;
      postDepartment(this.department).then(response => {
        vm.$message({
          type: 'success',
          message: 'Data Berhasil Disimpan'
        });
      })
    },
    deleteRow(index, items) {
      items.splice(index, 1)
    },
    addRow(items) {
      items.push({ areacode: '', areaname: '' })
    },
    addSubRow(items) {
      items.push({ subcode: '', subname: '' })
    },
    moveRow(index, items, inc) {
      var newindex = index + inc
      // console.log(newindex);
      if (newindex < 0) return
      if (newindex >= items.length) return
      var tmp = items[index]
      items[index] = items[newindex]
      // items[newindex] = tmp
      this.$set(items, newindex, tmp)
    }
  }
}
</script>

<style scoped>
  .el-input-number {
      width: 140px;
  }
</style>
