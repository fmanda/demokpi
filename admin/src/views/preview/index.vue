<template>
  <div class="demo-image__preview">
    <el-card class="box-card" >
      <div slot="header" class="clearfix">
        <span>Card name</span>
        <el-button style="float: right; padding: 3px 0" type="text">Operation button</el-button>
      </div>
      <div v-for="o in 4" :key="o" class="text item">
        {{'List item ' + o }}
      </div>
    </el-card>
    <!-- <el-image
      style="width: 200px; height: 200px"
      :src="url" fit="contains"
      :preview-src-list="[url]">
    </el-image> -->
  </div>

</template>
<script>
import { getFileListML } from '@/api/kpidept'

export default {
  data() {
    return {
      data: []
    }
  },
  methods: {
    fetchData(){
      getFileListML(2020, 1, 'L3.1', 1).then(response => {
        this.data = response.data;
      }).catch(() => {
        this.data = [];
      })
    }
  },
  created(){
    this.fetchData();
  }
}
</script>
