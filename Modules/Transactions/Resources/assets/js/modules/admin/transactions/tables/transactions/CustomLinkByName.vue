<template>
    <span>
        <router-link :to="{name: 'root.edit', params: { id: rowData.id }}" data-popup="tooltip" :title="rowData.title" v-if="can_edit">{{ rowData.full_name | str_limit(25) }}</router-link>
        <span v-if="!can_edit && !deleted">{{ rowData.full_name | str_limit(25) }}</span>
        <s class="text-danger"  v-if="deleted">{{ rowData.full_name | str_limit(25) }}</s>
    </span>

</template>

<script>
  import Vuex from 'vuex'
  export default {
    name: 'CustomLinkByName',
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    data () {
      return {
      }
    },
    computed: {
      ...Vuex.mapGetters({
        user: 'currentUser'
      }),
      deleted: function () {
        return this.rowData.deleted_at !== null
      },
      can_edit () {
        return !this.deleted && this.hasPermissionTo('UPDATE_USERS')
      }
    },
    methods: {
      hasPermissionTo (permission) {
        return this.user.hasOwnProperty('permissions') && (this.user.permissions.indexOf(permission) >= 0)
      }
    }
  }
</script>
