<template>
  <div class="app-container">
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
      </el-tab-pane>
    </el-tabs>

    <el-dialog :title="dialogData.caption" :visible.sync="dialogVisible" label-position="top">
      <el-upload
        class="upload-demo"
        ref="upload"
        :action="getRestUploadURL()"
        :on-error="handleUploadError"
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
import { getKPIDept } from '@/api/department'
import { spanRow } from '@/utils/spanRow'
import { getUploadURL } from '@/api/kpidept'
import { Message } from 'element-ui'

export default {
  data() {
    return {
      kpidept: {
        mlitems: [],
        kpiitems: []
      },
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
  created() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getKPIDept(1, 2020).then(response => {
        this.kpidept = response.data
        this.listLoading = false
      })
    },
    onSpanMethod({ row, column, rowIndex, columnIndex }) {
      return spanRow({ row, column, rowIndex, columnIndex }, this.kpidept.mlitems, this.optionSpan)
    },
    showDialog(index, items) {
      this.dialogData.caption = 'Upload Evident : ' + items[index].subname;
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
      if (row.level > 1 && columnIndex === 2){
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
      return getUploadURL();
    },
    handleUploadError(err, file, fileList){
      console.log(err);
      Message({
        message: 'Error Upload File, Cek console untuk detail',
        type: 'error',
        duration: 5 * 1000
      })
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
