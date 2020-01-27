<template>
    <div class="card-header header-elements-inline">
        <h5 class="card-title">
            <button type="button" class="btn btn-outline bg-teal-300 text-teal-800 btn-icon dropdown-toggle" data-toggle="dropdown">
                <i class="icon-gear"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-left">
                <router-link class="dropdown-item" :to="{name: 'root.edit', params: { id: 0 }}"  v-if="can_create"><i class="icon-plus3"></i> Create</router-link>
                <a href="javascript:;" v-on:click="onImport()" class="dropdown-item"><i class="icon-file-text3"></i> Import Languages</a>
                <a href="javascript:;" v-on:click="onExport()" class="dropdown-item"><i class="icon-file-text3"></i> Export Languages</a>
                <a href="javascript:;" v-on:click="onReset()" class="dropdown-item"><i class="icon-file-text3"></i> Reset Languages</a>
                <a href="javascript:;" v-on:click="dialogFormVisible = true" class="dropdown-item"><i class="icon-file-text3"></i> Clone Languages</a>
            </div>
        </h5>
        <div class="header-elements">
            <form action="#" class="row">
                <div class="col-xl-2 col-md-12 col-sm-12">
                    <div class="form-group">
                        <el-select v-model="locale" :placeholder="trans('tables.locale')" @input="doLocale">
                            <el-option style="width: 100%;"
                                       v-for="(key, item) in languages"
                                       :key="item"
                                       :label="key"
                                       :value="item">
                            </el-option>
                        </el-select>
                    </div>
                </div>
                <div class="col-xl-2 col-md-12 col-sm-12">
                    <div class="form-group">
                        <el-select :value="show" placeholder="Select" multiple collapse-tags style="margin-left: 2px;"  @input="doShow">
                            <el-option  style="width: 100%;"
                                    v-for="item in fields"
                                    :key="item.title"
                                    :label="item.title"
                                    :value="item.title">
                            </el-option>
                        </el-select>
                    </div>
                </div>
                <div class="col-xl-2 col-md-12 col-sm-12">
                    <div class="form-group">
                        <el-select v-model="value" placeholder="Select" @input="doPage">
                            <el-option  style="width: 100%;"
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Search" v-model="filterText" @input="doFilter">
                            <span class="input-group-append">
                            <button type="button" class="btn btn-default heading-btn" @click="resetFilter">Reset</button>
                        </span>
                        </div>
                        <div class="form-control-feedback">
                            <i class="icon-search4 font-size-sm text-muted"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <el-dialog title="Please choose a locale" :visible.sync="dialogFormVisible" width="35%">
            <el-form :model="form">
                <el-form-item label="From">
                    <el-col :span="8">
                        <el-select v-model="form.localeFrom" placeholder="Please choose a locale" style="width: 100%;">
                            <el-option
                                    v-for="(key, index) in languages"
                                    :key="index"
                                    :label="index"
                                    :value="index">
                                <span style="float: left">{{ index }}</span>
                            </el-option>
                        </el-select>
                    </el-col>
                    <el-col class="line" :span="2"> &nbsp;&nbsp;To</el-col>
                    <el-col :span="8">
                        <el-input v-model="form.localeTo"></el-input>
                    </el-col>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <button class="btn btn-outline-danger btn-sm" @click="dialogFormVisible = false"><i class="icon-cancel-circle2 mr-1"></i> {{ trans('button.cancel') }}</button>
                <button class="btn btn-outline bg-teal-600 text-teal-600 border-teal-600 btn-sm" @click="onClone()" v-if="form.localeTo !== null"> {{ trans('button.confirm') }}</button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
  import Vuex from 'vuex'
  import config from './config'
  export default {
    name: 'FilterBar',
    data () {
      return {
        dialogFormVisible: false,
        formLabelWidth: '120px',
        form: {
          localeFrom: null,
          localeTo: null,
          id: ''
        },
        locale: null,
        urlExport: window.laroute.route('admin.users.export'),
        fields: config.fields,
        filterText: '',
        value: 10,
        options: [{
          value: 10,
          label: '10'
        }, {
          value: 25,
          label: '25'
        }, {
          value: 50,
          label: '50'
        }, {
          value: 100,
          label: '100'
        }]
      }
    },
    computed: {
      ...Vuex.mapGetters({
        checked: 'checked',
        user: 'currentUser',
        languages: 'languages'
      }),
      can_create () {
        return this.hasPermissionTo('CREATE_USERS')
      },
      show () {
        let results = []
        if (this.fields.length > 0) {
          this.fields.forEach(function (element) {
            if (element.visible) {
              results.push(element.title)
            }
          })
        }

        return results
      },
      urlExportSelected () {
        return window.laroute.route('admin.users.export', {checked: this.checked})
      }
    },
    methods: {
      onClone () {
        this.dialogFormVisible = false
        this.$events.fire('clone-set', this.form.localeFrom, this.form.localeTo)
      },
      doLocale () {
        this.$events.fire('locale-set', this.locale)
      },
      hasPermissionTo (permission) {
        return this.user.hasOwnProperty('permissions') && (this.user.permissions.indexOf(permission) >= 0)
      },
      doShow (elements) {
        let filtered = elements.filter(function (el) {
          return el != null
        })
        this.fields.forEach(function (element) {
          if (filtered.indexOf(element.title) < 0) {
            if (element.visible === true) {
              element.visible = false
            }
          } else if (element.visible === false) {
            element.visible = true
          }
        })
        this.$events.fire('show-set', this.fields)
      },
      doPage () {
        this.$events.fire('page-set', this.value)
      },
      onImport () {
        this.$events.fire('import-set', this.value)
      },
      onExport () {
        this.$events.fire('export-set', this.value)
      },
      onReset () {
        this.$events.fire('reset-set', this.value)
      },
      doFilter () {
        if (this.filterText.length > 0) {
          this.$events.fire('filter-set', this.filterText)
        } else if (this.filterText.length === 0) {
          this.resetFilter()
        }
      },
      resetFilter () {
        this.filterText = ''
        this.$events.fire('filter-reset')
      }
    }
  }
</script>

