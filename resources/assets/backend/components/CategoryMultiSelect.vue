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
        categories: [],
        currentCategories: []
      };
    },
    methods: {
      onCheckChange (categories) {
        this.$emit('category_change', categories.filter(item => item.canSelect));
      },
      checkoutCategory () {
        this.categories.map(item => {
          if (item.children) {
            item.children.map(cItem => {
              cItem.checked = cItem.canSelect && (this.currentCategories.find(c => c === cItem.id) !== undefined);
            });
          }
          item.checked = item.canSelect && (this.currentCategories.find(c => c === item.id) !== undefined);
        });
      },
      setCurrentCategories (currentCategories) {
        this.currentCategories = currentCategories;
        this.checkoutCategory();
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
        this.checkoutCategory();
      });
    }
  };
</script>

<style scoped lang="less">

</style>
