<template>
  <div id="container" class="app-container">
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
          <el-table-column width="180" header-align="center" prop="levelcode" label="Level">
            <template slot-scope="sc">
              <el-tag :type="getTagType(sc)"> Lv {{ sc.row.level }}</el-tag>
              {{ sc.row.levelcode }}
            </template>
          </el-table-column>
          <el-table-column header-align="center" prop="leveldetail" label="Uraian" />
          <el-table-column width="70" header-align="center" prop="weight" label="Bobot">
            <template slot-scope="sc">
              <el-tag :type="getTagType(sc)" effect="plain"> {{ sc.row.weight }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column width="110" label="Operations" header-align="center">
            <template slot-scope="sc">
              <el-button-group>
                <el-button type="primary" size="small" icon="el-icon-folder-opened" @click.native.prevent="showOpenDlg(sc.$index, kpidept.mlitems, false)">
                  Open
                </el-button>
              </el-button-group>
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
          :span-method="onSpanMethodKPI"
        >
          <el-table-column width="150" header-align="center" prop="areaname" label="Area" />
          <el-table-column label="Sub Area" header-align="center">
            <el-table-column width="70" header-align="center" prop="subcode" label="No" />
            <el-table-column width="250" header-align="center" prop="subdesc" label="Sub Name" />
          </el-table-column>
          <el-table-column width="180" header-align="center" prop="levelcode" label="Level">
            <template slot-scope="sc">
              <el-tag :type="getTagType(sc)"> Lv {{ sc.row.level }}</el-tag>
              {{ sc.row.levelcode }}
            </template>
          </el-table-column>
          <el-table-column header-align="center" prop="leveldetail" label="Uraian" />
          <el-table-column width="70" header-align="center" prop="weight" label="Bobot">
            <template slot-scope="sc">
              <el-tag :type="getTagType(sc)" effect="plain"> {{ sc.row.weight }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column width="110" label="Operations" header-align="center">
            <template slot-scope="sc">
              <el-button-group>
                <el-button type="primary" size="small" icon="el-icon-folder-opened" @click.native.prevent="showOpenDlg(sc.$index, kpidept.kpiitems, true)">
                  Open
                </el-button>
              </el-button-group>
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
    </el-tabs>

    <el-dialog :title="dialogFile.caption" :visible.sync="dialogFileVisible" label-position="top" width="90%">
      <div v-for="(item, index) in dbFileList" :key="item.id">
        <el-table :data="getFileData(index)" border style="width: 100%" :show-header="false" :cell-style="cellStyleFile">
          <el-table-column prop="caption" label="" width="120" />
          <el-table-column label="">
            <template slot-scope="sc">
              <div v-if="(!sc.row.islink)&&(!sc.row.isimg)">{{ sc.row.value }}</div>
              <el-image v-if="sc.row.isimg" :src="sc.row.value" />
              <el-link v-if="sc.row.islink" :href="sc.row.value" type="primary" style="margin-bottom: 10px">Download File</el-link>
            </template>
          </el-table-column>
        </el-table>
        <br>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogFileVisible = false">Close</el-button>
      </span>
    </el-dialog>

  </div>
</template>

<script>
import { getKPIDept, getListDept } from '@/api/department'
import { getFileListML, getFileListKPI } from '@/api/kpidept'
import { spanRow } from '@/utils/spanRow'
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      listLoading: null,
      param_year: null,
      param_department_id: null,
      param_iskpi: null,
      param_subcode: null,
      param_level: null,
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
      dialogOpenVisible: false,
      fileList: [],
      dbFileList: [],

      dialogFile: {},
      dialogFileVisible: false
    }
  },
  computed: {
    ...mapGetters([
      'name', 'department_id'
    ])
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
    this.fetchDepts();
    // this.fetchData();
    this.param_year = new Date().getFullYear();
    // console.log(this.$route.params);
    if (this.$route.params) {
      this.param_department_id = this.$route.params.deptid;
      this.param_year = this.$route.params.year;
    }
  },
  methods: {
    fetchDepts() {
      getListDept().then(response => {
        this.depts = response.data;
        if (this.department_id > 0) {
          for (var i = this.depts.length - 1; i >= 0; i--) {
            if (this.depts[i].id !== this.department_id) {
              this.depts.splice(i, 1);
            }
          }
        }
      })
    },
    getFileData(index) {
      var item = this.dbFileList[index];
      var ar = [];
      ar.push({ caption: 'File Name', value: item.filename, link: process.env.VUE_APP_BASE_URL + '/downloadfile/' + item.id.toString() });
      ar.push({ caption: 'User Upload', value: item.username });
      ar.push({ caption: 'Upload Date', value: item.upload_date });

      if (item.filename.match(/.(jpg|jpeg|png|gif)$/i)) {
        ar.push({ caption: '', value: (
          process.env.VUE_APP_BASE_URL + '/downloadfile/' + item.id.toString()
        ), isimg: true, islink: true });
      } else {
        ar.push({ caption: '', value: (
          process.env.VUE_APP_BASE_URL + '/downloadfile/' + item.id.toString()
        ), islink: true });
      }

      return ar;
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

        var lvl = '';
        var indexLvl = 0;
        for (var item of this.kpidept.mlitems) {
          if (item.subcode === lvl) {
            item.indexLvl = indexLvl
          } else {
            lvl = item.subcode;
            indexLvl++;
            item.indexLvl = indexLvl;
          }
        }

        indexLvl = 0;
        for (item of this.kpidept.kpiitems) {
          if (item.subcode === lvl) {
            item.indexLvl = indexLvl
          } else {
            lvl = item.subcode;
            indexLvl++;
            item.indexLvl = indexLvl;
          }
        }
        this.listLoading = false;
      })
    },
    onSpanMethod({ row, column, rowIndex, columnIndex }) {
      return spanRow({ row, column, rowIndex, columnIndex }, this.kpidept.mlitems, this.optionSpan)
    },
    onSpanMethodKPI({ row, column, rowIndex, columnIndex }) {
      return spanRow({ row, column, rowIndex, columnIndex }, this.kpidept.kpiitems, this.optionSpan)
    },
    showOpenDlg(index, items, iskpi) {
      this.dialogFile.caption = 'Browse Evident : ' + items[index].subname;
      this.param_subcode = items[index].subcode;
      this.param_level = items[index].level;
      this.param_iskpi = iskpi;

      // console.log(this.param_iskpi);
      if (!this.param_year) return;
      if (!this.param_department_id) return;
      if (!this.param_subcode) return;
      if (!this.param_level) return;
      // if (!this.param_iskpi) return;
      this.getFileList();
      this.dialogFileVisible = true;
    },
    cellStyle({ row, column, rowIndex, columnIndex }) {
      var str = '';

      if ((row.indexLvl % 2) === 0) {
        str = str + ' background-color: rgb(255, 255, 228); '
      }

      if ((columnIndex === 0) || (row.level === 1 && columnIndex === 2) || (columnIndex === 1)) {
        str = str + ' font-weight: bold; color: rgb(64, 158, 255);'
      }

      if ((row.level > 1 && columnIndex === 2) || (columnIndex === 3)) {
        str = str + ' font-size: 13px; line-height:1;';
      }
      return str;
    },
    getTagType(sc) {
      if (sc.row.level === 1) return 'danger';
      if (sc.row.level === 2) return 'success';
      if (sc.row.level === 3) return 'info';
      if (sc.row.level === 4) return '';
      if (sc.row.level === 5) return 'success';
    },
    cellStyleFile({ row, column, rowIndex, columnIndex }) {
      var str = '';
      if (columnIndex === 1 && rowIndex === 0) {
        str = str + ' font-weight: bold;'
      }
      if (columnIndex === 0) {
        str = str + ' background-color: #304156; color: rgb(191, 203, 217);'
      }

      return str;
    },
    getFileList() {
      if (this.param_iskpi) {
        getFileListKPI(this.param_year, this.param_department_id, this.param_subcode, this.param_level).then(response => {
          this.dbFileList = response.data;
        }).catch(() => {
          this.dbFileList = [];
        })
      } else {
        getFileListML(this.param_year, this.param_department_id, this.param_subcode, this.param_level).then(response => {
          this.dbFileList = response.data;
        }).catch(() => {
          this.dbFileList = [];
        })
      }
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
