<template>
  <div class="category_multi_select">
    <Tree :data="categories" show-checkbox></Tree>
  </div>
</template>

<script>
  export default {
    name: 'CategoryMultiSelect',
    data () {
      return {
        categories: []
      };
    },
    mounted () {
      this.$http.get('categories', {
        params: {
          type: 'post'
        }
      }).then(res => {
        this.categories = res.data.data;
      });
    }
  };
</script>

<style scoped lang="less">

</style>
