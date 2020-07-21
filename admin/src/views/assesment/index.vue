<template>
  <div>
    <el-image
        style="width: 100px; height: 100px"
        :src="url"
    ></el-image>
    <br>
  </div>
</template>

<script>

export default {
  data() {
    return {
      url: 'http://127.0.0.1:8000/downloadfile/1'
    }
  }
}
</script>
