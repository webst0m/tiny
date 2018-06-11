<template>
  <div class="user">
    <Panel :title="title">
      <Form ref="form" :rules="rules" :model="formData" :label-width="100">
        <Form-item label="用户名" prop="user_name" :error="errors.user_name">
          <Input v-model="formData.user_name" placeholder="请设置用户名"></Input>
        </Form-item>
        <Form-item label="昵称" prop="nick_name" :error="errors.nick_name">
          <Input v-model="formData.nick_name" placeholder="请设置用户昵称"></Input>
        </Form-item>
        <Form-item label="email" prop="email" :error="errors.email">
          <Input v-model="formData.email" placeholder="请设置email"></Input>
        </Form-item>
        <Form-item label="密码" prop="password" :error="errors.password">
          <Input v-model="formData.password" type="password" placeholder="请设置密码 留空则保持原密码"></Input>
        </Form-item>
        <Form-item label="头像" :error="errors.avatar">
          <UploadPicture  @on-remove="() => formData.avatar = null" @on-success="avatar => formData.avatar = avatar" :url="formData.avatar_url" height="180px" class="upload_picture" />
        </Form-item>
        <Form-item v-show="$root.me.is_super_admin" label="允许编辑栏目" :error="errors.categories">
          <CategoryMultiSelect ref="CategoryMultiSelect" @category_change="categoryChange"/>
        </Form-item>
      </Form>
      <FormButtonGroup />
    </Panel>
  </div>
</template>
<script>
import Panel from '../../components/Panel.vue';
import fromMixin from '../../mixins/form';
import FormButtonGroup from '../../components/FormButtonGroup.vue';
import UploadPicture from '../../components/UploadPicture.vue';
import CategoryMultiSelect from '../../components/CategoryMultiSelect.vue';
export default {
  components: { Panel, FormButtonGroup, UploadPicture, CategoryMultiSelect },
  mixins: [ fromMixin ],
  computed: {
    rules () {
      return {
        user_name: [
          { required: true, type: 'string', message: '请填写用户名', trigger: 'blur' }
        ],
        nick_name: [
          { required: true, type: 'string', message: '请填写用户昵称', trigger: 'blur' }
        ],
        email: [
          { required: true, message: '邮箱不能为空', trigger: 'blur' },
          { type: 'email', message: '邮箱格式不正确', trigger: 'blur' }
        ],
        password: [
          { required: this.isAdd(), type: 'string', message: '请设置密码', trigger: 'blur' }
        ],
      };
    },
    mixinConfig () {
      return {
        title: '用户',
        query: {
          include: 'categories'
        },
        action: this.isAdd() ? 'users' : `users/${this.$route.params.id}`,
      };
    }
  },
  methods: {
    categoryChange (categories) {
      this.formData.categories = categories.map(item => item.id);
    }
  },
  mounted () {
    this.$on('on-success', () => {
      this.$router.push({name: 'userList'});
    });
    this.$on('on-data', () => {
      this.formData.categories = this.formData.categories.data.map(item => item.id);
      this.diffSave(this.formData);
      this.$refs['CategoryMultiSelect'].setCurrentCategories(this.formData.categories);
    });
  },
  data () {
    return {
      formData: {
        'user_name': null,
        'nick_name': null,
        'password': null,
        'email': null,
        'avatar': null,
        'roles': null,
        'permissions': null,
        'categories': null,
      }
    };
  }
};
</script>
<style lang="less" scoped>
.user{
  .upload_picture{
    width: 180px;
    margin-top: 10px;
  }
}
</style>
