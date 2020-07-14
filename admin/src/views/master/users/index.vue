<template>
  <h1>{{ test }}</h1>
</template>

<script>
import { test } from '@/api/test'
export default {
  data() {
    return {
      loading: false,
      test: 'hello'
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
    }
  }
}
</script>
