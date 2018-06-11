<template>
  <div class="category_multi_select">
    <Tree :data="categories" @on-check-change="onCheckChange" show-checkbox></Tree>
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
    methods: {
      onCheckChange (categorys) {
        this.$emit('category_change', categorys.filter(item => item.canSelect));
      }
    },
    mounted () {
      this.$http.get('categories', {
        params: {
          type: 'post'
        }
      }).then(res => {
        this.categories = res.data.data.map(item => {
          let children;
          if (item.children) {
            children = item.children.data.map(childrenItem => {
              return {
                title: childrenItem.cate_name,
                canSelect: true,
                id: childrenItem.id
              };
            });
          }
          return {
            title: item.cate_name,
            children,
            canSelect: children.length === 0,
            id: item.id
          };
        });
      });
    }
  };
</script>

<style scoped lang="less">

</style>
