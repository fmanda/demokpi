<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <el-input
          placeholder="Search File / Directory"
          v-model="filterText">
        </el-input>
      </div>
      <span>Directory List :</span>
      <p></p>
      <el-tree
        class="filter-tree"
        :data="data"
        :props="defaultProps"
        :filter-node-method="filterNode"
        icon-class="el-icon-caret-right"
        ref="tree">
      </el-tree>
    </el-card>
  </div>
</template>

<script>
import { getDirectory } from '@/api/directory'

export default {
  watch: {
    filterText(val) {
      this.$refs.tree.filter(val);
    }
  },
  data() {
    return {
      filterText: '',
      data: null,
      listLoading: true,
      defaultProps: {
        children: 'items',
        label: 'fileName'
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getDirectory().then(response => {
        this.data = response.data.items;
        this.listLoading = false
      })
    },
    filterNode(value, data) {
      if (!value) return true;
      return data.fileName.toLowerCase().indexOf(value.toLowerCase()) !== -1;
    }
  }
}
</script>
