<template>
    <!-- Large modal -->
    <div id="modal_large_transaction" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h5 class="modal-title">
                        <i class="icon-menu7 mr-2"></i>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0 nav-justified" v-if="can_logs">
                    <li class="nav-item"><a href="#highlighted-justified-tab1" class="nav-link active" data-toggle="tab"><i class="icon-pencil6 mr-2"></i></a></li>
                    <li class="nav-item"><a href="#highlighted-justified-tab2" class="nav-link" data-toggle="tab"><i class="icon-file-text mr-2"></i></a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                        <div class="card-body">
                            <form action="#" @submit.prevent="onSubmit" @keydown="errors.clear($event.target.name)">
                            <div class="modal-body">
                                <fieldset>
                                    <legend class="font-weight-semibold text-uppercase font-size-sm">
                                        <i class="icon-file-text2 mr-2"></i>
                                        Enter your information
                                        <a class="float-right text-default" data-toggle="collapse" data-target="#demo1">
                                            <i class="icon-circle-down2"></i>
                                        </a>
                                    </legend>
                                    <div class="collapse show" id="demo1">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">&nbsp;{{ trans('modals.id') }} <span class="text-danger">&nbsp;* </span></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" :class="errors.has('id') ? 'is-invalid': ''" id='id' name='id' :placeholder="trans('modals.id')" aria-readonly="true" disabled="true" :value="transaction.id"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">&nbsp;{{ trans('modals.code') }} <span class="text-danger">&nbsp;* </span></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" :class="errors.has('code') ? 'is-invalid': ''" :placeholder="trans('modals.code')" aria-readonly="true" disabled="true" :value="transaction.code"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">&nbsp;{{ trans('modals.method') }} <span class="text-danger">&nbsp;* </span></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" :class="errors.has('method') ? 'is-invalid': ''" :placeholder="trans('modals.method')" aria-readonly="true" disabled="true" :value="transaction.method"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">&nbsp;{{ trans('modals.amount') }} <span class="text-danger">&nbsp;* </span></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" :class="errors.has('amount') ? 'is-invalid': ''" :placeholder="trans('modals.amount')" aria-readonly="true" disabled="true" :value="formatNumber(transaction.amount)"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">&nbsp;{{ trans('modals.items') }} <span class="text-danger">&nbsp;* </span></label>
                                            <div class="col-lg-9">
                                                <table class="table table-striped table-responsive">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">{{ trans('tables.name') }}</th>
                                                        <th scope="col">{{ trans('tables.qty') }}</th>
                                                        <th scope="col">{{ trans('tables.amount')}}</th>
                                                        <th scope="col">{{ trans('tables.tax')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="order in transaction.orders">
                                                        <th scope="row">{{ order.id }}</th>
                                                        <td>{{ order.name }}</td>
                                                        <td>{{ order.qty }}</td>
                                                        <td>{{ formatNumber(order.price) }}</td>
                                                        <td>{{ order.tax }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-outline bg-teal-600 text-teal-600 border-teal-600 btn-sm" v-on:click="close = false"><i class="icon-checkmark-circle mr-1"></i>{{ trans('button.save') }}</button>
                                <button type="submit" class="btn btn-outline bg-teal-400 text-teal-400 border-teal-400 btn-sm" v-on:click="close = true"><i class="icon-checkmark-circle mr-1"></i>{{ trans('button.save_and_close') }}</button>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i> {{ trans('button.close') }}</button>
                            </div>
                        </form>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="highlighted-justified-tab2" v-if="can_logs">
                        <div class="modal-body">
                            <vue-table :options="transaction.activities"></vue-table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i> {{ trans('button.close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /large modal -->
</template>

<script>
  import Vuex from 'vuex'
  import accounting from 'accounting'
  import { Errors } from '../../../../../../../../../resources/js/utils/errors'
  import VueTable from '../../../../../../../../../resources/js/utils/Table.vue'
  import UploadAttachments from '../../../../../../../../../resources/js/utils/UploadAttachments.vue'
  export default {
    name: 'EditTransactionComponent',
    components: { VueTable, UploadAttachments },
    data () {
      return {
        // eslint-disable-next-line
        errors: new Errors(),
        close: false
      }
    },
    mounted () {
      this.loadModal()
    },
    watch: {
      '$route.params.id' () {
        this.EditTransaction(parseInt(this.$route.params.id))
      }
    },
    computed: {
      ...Vuex.mapGetters({
        transaction: 'transaction',
        currentUser: 'currentUser'
      }),
      can_logs () {
        return this.hasPermissionTo('LOGS_TRANSACTIONS')
      }
    },
    methods: {
      ...Vuex.mapActions({
        addTransaction: 'addTransaction'
      }),
      formatNumber (value) {
        return accounting.formatMoney(value, '€', 2, ',', '.')
      },
      getTransaction (key, value) {
        return (this.transaction.hasOwnProperty(key)) ? this.template[key][value] : ''
      },
      hasPermissionTo (permission) {
        return this.currentUser.hasOwnProperty('permissions') && (this.currentUser.permissions.indexOf(permission) >= 0)
      },
      hasRoleTo (role) {
        return this.currentUser.hasOwnProperty('roles') && (this.currentUser.roles.indexOf(role) >= 0)
      },
      updateTransaction (e) {
        if (e.target.value !== null) {
          this.$store.commit('updateTransaction', {name: e.target.name, value: e.target.value})
        }
      },
      updateLocale (value) {
        this.$store.commit('updateTransaction', {name: 'locale', value: value})
        this.errors.clear('locale')
      },
      loadModal () {
        let that = this
        let id = parseInt(this.$route.params.id)
        $('#modal_large_transaction').on('hidden.bs.modal', () => {
          that.$router.push({name: 'root'})
        })

        this.EditTransaction(id)
      },
      EditTransaction (id) {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
        this.$http.get(window.laroute.route('admin.transactions.edit', {id: id}))
          .then(this.onLoadTransactionSuccess)
          .catch(this.onFailed)
          .then(() => {
            this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
          })
      },
      onLoadTransactionSuccess (response) {
        if (response.data.hasOwnProperty('success') && response.data.success === true) {
          this.addTransaction(response.data.transaction)
          if (!($('#modal_large_transaction').data('bs.modal') || {}).isShown) {
            $('#modal_large_transaction').modal('show')
          }
        } else {
          this.$notify.error({ title: 'Error', message: response.data.message })
        }
      },
      onSubmit (e) {
        let id = parseInt(this.$route.params.id)
        if (id === 0) {
          this.onSubmitStore()
        } else {
          this.onSubmitUpdate(id)
        }
      },
      onSubmitStore () {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
        this.$http.put(window.laroute.route('admin.transactions.store'), this.transaction)
          .then(this.onSubmitSuccess)
          .catch(this.onFailed)
          .then(() => {
            this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
          })
      },
      onSubmitUpdate (id) {
        this.$store.dispatch('block', {element: 'transactionsComponent', load: true})
        this.$http.put(window.laroute.route('admin.transactions.update', {id: id}), this.transaction)
          .then(this.onSubmitSuccess)
          .catch(this.onFailed)
          .then(() => {
            this.$store.dispatch('block', {element: 'transactionsComponent', load: false})
          })
      },
      onSubmitSuccess (response) {
        if (response.data.hasOwnProperty('success') && response.data.success === true) {
          if (this.close) {
            $('#modal_large_transaction').modal('hide')
            this.$router.push({name: 'root'})
          } else {
            this.$router.push({name: 'root.edit', params: {id: response.data.transaction.id}})
          }
          this.$message({
            message: response.data.message,
            showClose: true,
            type: 'success'
          })
          this.$parent.$children[0].refresh()
        } else {
          this.$notify.error({ title: 'Error', message: response.data.message })
        }
      },
      onFailed (error) {
        if (error.response !== undefined && error.response.hasOwnProperty('data') && error.response.data.hasOwnProperty('errors')) {
          this.errors.record(error.response.data.errors)
          if (error.response.data.hasOwnProperty('success') && error.response.data.hasOwnProperty('message')) {
            this.$notify.error({ title: 'Failed', message: error.response.data.message })
          } else {
            this.$notify.error({ title: 'Failed', dangerouslyUseHTMLString: true, message: this.errors.getErrors(this.errors.errors) })
          }
        } else if (error.response !== undefined && error.response.hasOwnProperty('data') && error.response.data.hasOwnProperty('message')) {
          this.$notify.error({ title: 'Failed', message: error.response.data.message })
        } else if (error.hasOwnProperty('message')) {
          this.$notify.error({ title: 'Error', message: error.message })
        } else {
          this.$notify.error({ title: 'Failed', message: 'Service not answer, Please contact your Support' })
          console.log(error)
        }
      }
    }
  }
</script>
