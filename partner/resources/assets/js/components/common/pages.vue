<template>
  <div class="Pagination text-center">
    <Page :total="count" show-elevator  @on-change="setChangePage" size="small"></Page>
  </div>
</template>
<script>
export default {
    props: ['count'],
    methods: {
        setChangePage: function (page) {
            this.$emit('transferpage', page)
        }
    }
}
</script>
