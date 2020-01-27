
<script>
  import Vue from 'vue'
  import Vuex from 'vuex'
  import accounting from 'accounting'
  import moment from 'moment'

  import Vuetable from 'vuetable-2/src/components/Vuetable.vue'
  import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
  import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
  import config from './config'
  import FilterBar from './FilterBar'
  import CssConfig from './CssConfig.js'
  Vue.component('filter-bar', FilterBar)
  Vue.component('my-detail-row', config.detail)
  Vue.component('custom-actions', config.actions)
  Vue.component('custom-link-by-id', config.customLinkById)
  Vue.component('custom-link-by-name', config.customLinkByName)
  Vue.component('custom-status', config.CustomStatus)
  moment.locale(window.i18.lang)

  export default {
    name: 'VueDatatable',
    props: {
    },
    data () {
      return {
        apiUrl: window.laroute.route('admin.transactions.view'),
        fields: config.fields,
        sortOrder: config.sortOrder,
        appendParams: config.moreParams,
        perPage: config.perPage,
        detailRowComponent: 'my-detail-row',
        rowClass: 'context',
        css: CssConfig,
        loading: false
      }
    },
    components: { Vuetable, VuetablePagination, VuetablePaginationInfo },
    mounted () {
      this.$events.$on('filter-set', eventData => this.onFilterSet(eventData))
      this.$events.$on('show-set', fields => this.onShowSet(fields))
      this.$events.$on('view-set', id => this.onViewSet(id))
      this.$events.$on('page-set', perPage => this.onPageSet(perPage))
      this.$events.$on('filter-reset', e => this.onFilterReset())
      this.$events.$on('delete-set', (id) => this.doDelete(id))
      this.$events.$on('locale-set', (locale) => this.doLocale(locale))
      this.$events.$on('import-set', () => this.doImport())
      this.$events.$on('export-set', () => this.doExport())
      this.$events.$on('reset-set', () => this.doReset())
      this.$events.$on('clone-set', (from, to) => this.doClone(from, to))
    },
    render (h) {
      return h(
        'div',
        {
          class: { 'card': true }
        },
        [
          h('filter-bar'),
          this.renderCardBody(h),
          this.renderMain(h)
        ]
      )
    },
    methods: {
      ...Vuex.mapActions({
        addChecked: 'addChecked',
        addCheckedId: 'addCheckedId',
        removeCheckedId: 'removeCheckedId'
      }),
      refresh () {
        Vue.nextTick(() => this.$refs.vuetable.refresh())
      },
      onFilterSet (filterText) {
        this.appendParams.filter = filterText
        Vue.nextTick(() => this.$refs.vuetable.refresh())
      },
      onShowSet (fields) {
        this.fields = fields
        Vue.nextTick(() => this.$refs.vuetable.normalizeFields(this.fields))
      },
      onViewSet (id) {
        this.$refs.vuetable.toggleDetailRow(id)
      },
      onPageSet (perPage) {
        this.perPage = perPage
        Vue.nextTick(() => this.$refs.vuetable.refresh())
      },
      onFilterReset () {
        delete this.appendParams.filter
        Vue.nextTick(() => this.$refs.vuetable.refresh())
      },
      renderLoader (h) {
        if (this.loading) {
          return h(
            'div',
            { class: { 'loading': true } },
            [
              this.renderSvg(h)
            ]
          )
        }
      },
      renderCardBody (h) {
        return h(
          'div',
          { class: { 'card-body': true } }
        )
      },
      renderMain (h) {
        return h(
          'div',
          { class: { 'table-responsive': true } },
          [
            this.renderLoader(h),
            this.renderVuetable(h),
            this.renderPagination(h)
          ]
        )
      },
      renderSvg (h) {
        return h(
          'div',
          { class: { 'loader': true } }
        )
      },
      // render related functions
      renderVuetable (h) {
        return h(
          'vuetable',
          {
            ref: 'vuetable',
            props: {
              apiUrl: this.apiUrl,
              fields: this.fields,
              paginationPath: '',
              perPage: this.perPage,
              multiSort: true,
              sortOrder: this.sortOrder,
              appendParams: this.appendParams,
              detailRowComponent: this.detailRowComponent,
              rowClass: this.rowClass,
              css: this.css.table
            },
            on: {
              'vuetable:checkbox-toggled': this.onCheckboxToggled,
              'vuetable:checkbox-toggled-all': this.onCheckboxToggledAll,
              'vuetable:cell-clicked': this.onCellClicked,
              'vuetable:pagination-data': this.onPaginationData,
              'vuetable:loading': this.onLoading,
              'vuetable:loaded': this.onLoaded
            },
            scopedSlots: this.$vnode.data.scopedSlots
          }
        )
      },
      renderPagination (h) {
        return h(
          'div',
          { class: {'datatable-footer': true} },
          [
            h('vuetable-pagination-info', { ref: 'paginationInfo', props: { css: this.css.paginationInfo } }),
            h('vuetable-pagination', {
              ref: 'pagination',
              props: { css: this.css.pagination },
              on: {
                'vuetable-pagination:change-page': this.onChangePage
              }
            })
          ]
        )
      },
      onPaginationData (paginationData) {
        this.$refs.pagination.setPaginationData(paginationData)
        this.$refs.paginationInfo.setPaginationData(paginationData)
      },
      onChangePage (page) {
        this.$refs.vuetable.changePage(page)
      },
      onCellClicked (data, field, event) {
        // this.$refs.vuetable.toggleDetailRow(data.id)
      },
      onCheckboxToggled (checked, data) {
        if (checked) {
          this.addCheckedId(data.id)
        } else {
          this.removeCheckedId(data.id)
        }
      },
      onCheckboxToggledAll (checked) {
        let data = []
        if (checked) {
          this.$refs.vuetable.tableData.forEach((row, index) => {
            data.push(row.id)
          })
        }

        this.addChecked(data)
      },
      onLoading () {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
      },
      onLoaded () {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
      },
      urlCallback (url) {
        let urlName = url.length <= 25 ? url : url.substring(0, 25) + ' ...'
        return '<a  href="' + url + '" data-popup="tooltip" title="' + url + '" target="_blank">' + urlName + '</a>'
      },
      formatNumber (value) {
        return accounting.formatMoney(value, '€', 2, ',', '.')
      },
      formatDate (value, fmt = 'D MMM YYYY') {
        return (value == null)
          ? ''
          : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
      },
      doDelete (id) {
        this.$confirm(this.trans('messages.delete'), 'Warning', {
          confirmButtonText: this.trans('labels.ok'),
          cancelButtonText: this.trans('labels.cancel'),
          type: 'warning'
        }).then(() => {
          this.onDelete(id)
        }).catch(() => {
          this.$message({
            type: 'info',
            message: this.trans('messages.delete_canceled')
          })
        })
      },
      doLocale (locale) {
        this.appendParams.locale = locale
        Vue.nextTick(() => this.$refs.vuetable.refresh())
      },
      doImport () {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
        this.$http.get(window.laroute.route('admin.transactions.import'))
          .then(this.onImportSuccess)
          .catch(this.onFailed)
          .then(() => {
            this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
          })
      },
      doExport () {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
        this.$http.get(window.laroute.route('admin.transactions.export'))
          .then(this.onExportSuccess)
          .catch(this.onFailed)
          .then(() => {
            this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
          })
      },
      doReset () {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
        this.$http.get(window.laroute.route('admin.transactions.reset'))
          .then(this.onExportSuccess)
          .catch(this.onFailed)
          .then(() => {
            this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
          })
      },
      doClone (from, to) {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
        this.$http.get(window.laroute.route('admin.transactions.clone', {from: from, to: to}))
          .then(this.onExportSuccess)
          .catch(this.onFailed)
          .then(() => {
            this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
          })
      },
      onDelete (id) {
        this.$http.delete(window.laroute.route('admin.transactions.destroy', {id: id}))
          .then(this.onDeleteSuccess)
          .catch(this.onFailed)
          .then(() => {
          })
      },
      onDeleteSuccess (response) {
        if (response.data.hasOwnProperty('success') && response.data.success === true) {
          this.$message({
            type: 'success',
            message: response.data.message
          })
          Vue.nextTick(() => this.$refs.vuetable.refresh())
        } else {
          this.$notify.error({ title: 'Error', message: response.message })
        }
      },
      onImportSuccess (response) {
        if (response.data.hasOwnProperty('success') && response.data.success === true) {
          this.$message({
            type: 'success',
            message: response.data.message
          })
          Vue.nextTick(() => this.$refs.vuetable.refresh())
        } else {
          this.$notify.error({ title: 'Error', message: response.message })
        }
      },
      onExportSuccess (response) {
        if (response.data.hasOwnProperty('success') && response.data.success === true) {
          this.$message({
            type: 'success',
            message: response.data.message
          })
          Vue.nextTick(() => this.$refs.vuetable.refresh())
        } else {
          this.$notify.error({ title: 'Error', message: response.message })
        }
      },
      onFailed (error) {
        if (error.response !== undefined && error.response.hasOwnProperty('data') && error.response.data.hasOwnProperty('errors')) {
          this.errors.record(error.response.data.errors)
          if ((error.response.data.hasOwnProperty('success') && error.response.data.success === false) || error.response.data.hasOwnProperty('message')) {
            this.$notify.error({ title: 'Error', message: error.response.data.message })
          } else {
            this.$notify.error({ title: 'Error', message: this.errors.getErrors(this.errors.errors) })
          }
        } else if (error.response !== undefined && error.response.hasOwnProperty('data') && error.response.data.hasOwnProperty('message')) {
          this.$notify.error({ title: 'Error', message: error.response.data.message })
        } else if (error.hasOwnProperty('message')) {
          this.$notify.error({ title: 'Error', message: error.message })
        } else {
          this.$notify.error({ title: 'Error', message: 'Service not answer, Please contact your Support' })
          console.log(error)
        }
      }
    }
  }
</script>
