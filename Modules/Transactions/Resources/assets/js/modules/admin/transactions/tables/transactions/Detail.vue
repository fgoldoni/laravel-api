<template>
    <div @click="onClick">
        <pre v-if="rowData.data" v-html="prettyJSON(rowData.data)"></pre>
    </div>
</template>
<style>
    pre {
        overflow: auto;
    }
    pre .string { color: #885800; }
    pre .number { color: blue; }
    pre .boolean { color: magenta; }
    pre .null { color: red; }
    pre .key { color: green; }
</style>
<script>
  export default {
    name: 'Detail',
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    methods: {
      onClick (event) {
        console.log('my-detail-row: on-click', event.target)
      },
      prettyJSON (json) {
        if (json) {
          json = JSON.stringify(json, undefined, 4)
          json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>')
          // eslint-disable-next-line
          return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number'
            if (/^"/.test(match)) {
              if (/:$/.test(match)) {
                cls = 'key'
              } else {
                cls = 'string'
              }
            } else if (/true|false/.test(match)) {
              cls = 'boolean'
            } else if (/null/.test(match)) {
              cls = 'null'
            }
            return '<span class="' + cls + '">' + match + '</span>'
          })
        }
      }
    }
  }
</script>
