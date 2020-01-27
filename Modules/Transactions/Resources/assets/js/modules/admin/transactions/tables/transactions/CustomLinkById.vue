<template>
    <span>
        <router-link :to="{name: 'root.edit', params: { id: rowData.id }}" data-popup="tooltip" :title="rowData.id" v-if="can_edit" v-text="rowData.id"></router-link>
        <span v-if="!can_edit">{{ rowData.id }}</span>
    </span>
</template>

<script>
  import Vuex from 'vuex'
  export default {
    name: 'CustomLinkById',
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
      can_edit () {
        return this.hasPermissionTo('UPDATE_TRANSLATIONS')
      }
    },
    methods: {
      hasPermissionTo (permission) {
        return this.user.hasOwnProperty('permissions') && (this.user.permissions.indexOf(permission) >= 0)
      }
    }
  }
</script>
