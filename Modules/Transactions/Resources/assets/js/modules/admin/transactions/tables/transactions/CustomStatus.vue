<template>
    <div class="panel-body">

    </div>
</template>


<script>
  export default {
    name: 'CustomStatus',
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
      statusLabel () {
        if (this.rowData.email_verified_at !== null) {
          return '<span  class="badge badge-light badge-striped badge-striped-left border-left-success">' + window.Lang.get('tables.activated') + '</span>'
        } else {
          return '<span  class="badge badge-light badge-striped badge-striped-left border-left-danger">' + window.Lang.get('tables.pending') + '</span>'
        }
      }
    },
    methods: {

    }
  }
</script>
