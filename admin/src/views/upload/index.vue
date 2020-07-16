<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane>
        <span slot="label"><i class="el-icon-s-opportunity" />Maturity Level</span>
        <el-table v-loading="listLoading" :data="kpidept.mlitems" style="width: 100%" :span-method="onSpanMethod">
          <el-table-column width="150" header-align="center" prop="areaname" label="Area"></el-table-column>
          <el-table-column label="Sub Area" header-align="center">
            <el-table-column width="70" header-align="center" prop="subcode" label="No"></el-table-column>
            <el-table-column width="250" header-align="center" prop="subdesc" label="Sub Name"></el-table-column>
          </el-table-column>
          <el-table-column width="180" header-align="center" prop="level" label="Name"></el-table-column>
          <el-table-column header-align="center" prop="leveldetail" label="Uraian"></el-table-column>
          <el-table-column width="180" label="Operations" header-align="center">
            <template slot-scope="sc">
              <el-button type="primary" @click.native.prevent="showDialog(sc.$index, kpidept.mlitems)">
                <i class="el-icon-upload el-icon-right"></i>  Upload
                {{sc.row.key}}
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
        action="https://jsonplaceholder.typicode.com/posts/"
        multiple
        :file-list="fileList">
        <el-button type="primary">Click to upload</el-button>
      </el-upload>

      <!-- <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">Upload</el-button>
        <el-button @click="dialogVisible = false">Cancel</el-button>
      </span> -->
    </el-dialog>
  </div>
</template>


<script>
import { getKPIDept, postDepartment } from '@/api/department'
import { spanRow } from '@/utils/spanRow'

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
      getKPIDept(1,2020).then(response => {
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
</style>
