<template>
    <div class="list-icons">
	    <a href="javascript:;" class="btn btn-outline btn-sm bg-grey text-grey-800 btn-icon ml-2" @click="doView(rowData.id)" v-if="can_view" data-popup="tooltip" :title="trans('labels.view')"><i class="icon-eye2"></i></a>
        <router-link class="btn btn-outline btn-sm bg-teal text-teal-800 btn-icon ml-2" :to="{name: 'root.edit', params: { id: rowData.id }}"  v-if="can_edit" data-popup="tooltip" :title="trans('labels.edit')"><i class="icon-pencil7"></i></router-link>
    </div>
</template>

<script>
  import Vuex from 'vuex'
  export default {
    name: 'Actions',
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    computed: {
      ...Vuex.mapGetters({
        user: 'currentUser'
      }),
      can_view () {
        return this.hasPermissionTo('ADMIN')
      },
      can_edit () {
        return this.hasPermissionTo('ADMIN')
      },
      can_delete () {
        return this.hasPermissionTo('ADMIN')
      }
    },
    methods: {
      doView (action, data, index) {
        this.$events.fire('view-set', action, data, index)
      },
      doDelete (id) {
        // this.$events.fire('delete-set', id)
      },
      doDestroy (id) {
        this.$events.fire('destroy-set', id)
      },
      hasPermissionTo (permission) {
        return this.user.hasOwnProperty('permissions') && (this.user.permissions.indexOf(permission) >= 0)
      },
      hasRoleTo (role) {
        return this.user.hasOwnProperty('roles') && (this.user.roles.indexOf(role) >= 0)
      }
    }
  }
</script>

<style>
</style>
