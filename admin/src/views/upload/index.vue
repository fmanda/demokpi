<template>
  <div class="app-container">
    <el-form ref="form" :model="dialogData" label-width="120px" label-position="top">
      <el-form-item label="">
        <el-col :span="2">
          <el-select v-model="param_year" placeholder="Select Year" style="width:100%">
            <el-option
              v-for="year in years"
              :key="year.id"
              :label="year.id"
              :value="year.id"
            />
          </el-select>
        </el-col>
        <el-col :span="22">
          <el-select v-model="param_department_id" placeholder="Select Department" style="width:100%; margin-left:10px">
            <el-option
              v-for="dept in depts"
              :key="dept.id"
              :label="dept.deptname"
              :value="dept.id"
            >
              <span style="float: left">{{ dept.deptname }}</span>
              <span style="float: right; color: #8492a6; font-size: 13px">{{ dept.deptcode }}</span>
            </el-option>
          </el-select>
        </el-col>
      </el-form-item>
    </el-form>

    <el-tabs type="border-card">
      <el-tab-pane>
        <span slot="label"><i class="el-icon-s-opportunity" />Maturity Level</span>
        <el-table
          v-loading="listLoading"
          :data="kpidept.mlitems"
          :cell-style="cellStyle"
          style="width: 100%"
          :span-method="onSpanMethod"
        >
          <el-table-column width="150" header-align="center" prop="areaname" label="Area" />
          <el-table-column label="Sub Area" header-align="center">
            <el-table-column width="70" header-align="center" prop="subcode" label="No" />
            <el-table-column width="250" header-align="center" prop="subdesc" label="Sub Name" />
          </el-table-column>
          <el-table-column width="180" header-align="center" prop="levelcode" label="Level" />
          <el-table-column header-align="center" prop="leveldetail" label="Uraian" />
          <el-table-column width="70" header-align="center" prop="weight" label="Bobot" />
          <el-table-column width="100" label="Operations" header-align="center">
            <template slot-scope="sc">
              <el-button type="text" @click.native.prevent="showDialog(sc.$index, kpidept.mlitems)">
                <i class="el-icon-upload2" />  Upload
                <!-- {{sc.row.key}} -->
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
      <el-tab-pane>
        <span slot="label"><i class="el-icon-s-opportunity" />KPI</span>
        <el-table
          v-loading="listLoading"
          :data="kpidept.kpiitems"
          :cell-style="cellStyle"
          style="width: 100%"
          :span-method="onSpanMethod"
        >
          <el-table-column width="150" header-align="center" prop="areaname" label="Area" />
          <el-table-column label="Sub Area" header-align="center">
            <el-table-column width="70" header-align="center" prop="subcode" label="No" />
            <el-table-column width="250" header-align="center" prop="subdesc" label="Sub Name" />
          </el-table-column>
          <el-table-column width="180" header-align="center" prop="levelcode" label="Level" />
          <el-table-column header-align="center" prop="leveldetail" label="Uraian" />
          <el-table-column width="70" header-align="center" prop="weight" label="Bobot" />
          <el-table-column width="100" label="Operations" header-align="center">
            <template slot-scope="sc">
              <el-button type="text" @click.native.prevent="showDialog(sc.$index, kpidept.kpiitems)">
                <i class="el-icon-upload2" />  Upload
                <!-- {{sc.row.key}} -->
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
    </el-tabs>

    <el-dialog :title="dialogData.caption" :visible.sync="dialogVisible" label-position="top">
      <el-upload
        ref="upload"
        class="upload-demo"
        :action="getRestUploadURL()"
        :on-error="handleUploadError"
        :on-success="handleUploadSuccess"
        multiple
        :file-list="fileList"
        :auto-upload="false"
      >
        <el-button slot="trigger" size="small" type="primary">select file</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">upload to server</el-button>
      </el-upload>

      <!-- <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">Upload</el-button>
        <el-button @click="dialogVisible = false">Cancel</el-button>
      </span> -->
    </el-dialog>
  </div>
</template>

<script>
import { getKPIDept, getListDept } from '@/api/department'
import { spanRow } from '@/utils/spanRow'
import { getUploadURL } from '@/api/kpidept'
import { Message } from 'element-ui'

export default {
  data() {
    return {
      param_year: null,
      param_department_id: null,
      param_deptcode: null,
      param_subcode: null,
      years: [{ id: 2020 }, { id: 2021 }, { id: 2022 }, { id: 2023 }, { id: 2024 }],
      kpidept: {
        mlitems: [],
        kpiitems: []
      },
      depts: [],
      optionSpan: [
        { index: 0, field: 'areaname' },
        { index: 1, field: 'subcode' },
        { index: 2, field: 'subdesc' }
      ],
      dialogData: {},
      dialogVisible: false,
      fileList: []
    }
  },
  watch: {
    param_year(val) {
      this.fetchData();
    },
    param_department_id(val) {
      this.fetchData();
    }
  },
  created() {
    this.fetchDepts()
    this.fetchData();
  },
  methods: {
    fetchDepts() {
      getListDept().then(response => {
        this.depts = response.data;
      })
    },
    fetchData() {
      this.listLoading = true
      var deptid = 0;
      var year = 0;
      if (this.param_department_id) deptid = this.param_department_id;
      if (this.param_year) year = this.param_year;

      if (year === 0 || deptid === 0) {
        this.listLoading = false;
        return;
      }

      getKPIDept(deptid, year).then(response => {
        this.kpidept = response.data;
        this.listLoading = false;
      })
    },
    onSpanMethod({ row, column, rowIndex, columnIndex }) {
      return spanRow({ row, column, rowIndex, columnIndex }, this.kpidept.mlitems, this.optionSpan)
    },
    showDialog(index, items) {
      this.dialogData.caption = 'Upload Evident : ' + items[index].subname;
      this.param_subcode = items[index].subcode;
      this.param_deptcode = this.kpidept.deptcode;
      if (!this.param_year) return;
      if (!this.param_deptcode) return;
      if (!this.param_subcode) return;
      this.dialogVisible = true;
    },
    cellStyle({ row, column, rowIndex, columnIndex }) {
      if (columnIndex === 0) {
        return 'font-weight: bold; color: rgb(64, 158, 255);'
      }
      if (row.level === 1 && columnIndex === 2) {
        return 'font-weight: bold; color: rgb(64, 158, 255);'
      }
      if (columnIndex === 3) {
        return 'color: rgb(64, 158, 255);'
      }
      if (columnIndex === 1) {
        return 'font-weight: bold; color: rgb(64, 158, 255);'
      }
      if (row.level > 1 && columnIndex === 2) {
        return 'font-size: 13px';
      }
      // if (columnIndex === 4) {
      //   return 'font-size: 13px';
      // }

      // if ([4,5].includes(columnIndex)){
      // if (columnIndex === 3) {
      //   if (row.level === 1) return 'color:#CC0033;';
      //   if (row.level === 2) return 'color:#993300;';
      //   if (row.level === 3) return 'color:#FF3300;';
      //   if (row.level === 4) return 'color:#0000FF;';
      //   if (row.level === 5) return 'color:#006600;';
      // }
    },
    submitUpload() {
      this.$refs.upload.submit();
    },
    getRestUploadURL() {
      var url = getUploadURL(this.param_year, this.param_deptcode, this.param_subcode);
      // console.log(url);
      return url;
    },
    handleUploadError(err, file, fileList) {
      console.log(err);
      Message({
        message: 'Error Upload File, Cek console untuk detail',
        type: 'error',
        duration: 5 * 1000
      })
    },
    handleUploadSuccess(response, file, fileList){
      console.log('sucess');
      this.dialogVisible = false;
      this.$message({
        type: 'success',
        message: response
      });
    }

  }
}
</script>

<style scoped>
  .el-table{
    /* table-layout: fixed; */
    /* overflow-wrap: break-word;
    word-break: break-word; */
  }
  .el-table >>> .cell {
    word-break: break-word;
    white-space: pre-wrap;
    /* word-break: normal;
    line-height: 15px;
    font-size: 12px; */
    /* white-space: pre; */
    /* text-overflow: clip; */
    /* overflow-wrap: break-word; */
  }

  .el-table >>> thead {
    color: rgb(191, 203, 217);;
    font-weight: 500;
    /* background: #304156;   */
  }

  .el-table >>> thead.is-group th {
    background: #304156;
  }
</style>
