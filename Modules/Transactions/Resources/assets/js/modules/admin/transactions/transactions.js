import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../../../vuex/store'
import Lang from '../../../../../../../../resources/js/utils/lang'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import de from 'element-ui/lib/locale/lang/de'
import en from 'element-ui/lib/locale/lang/en'
import fr from 'element-ui/lib/locale/lang/fr'
import locale from 'element-ui/lib/locale'
import VueEvents from 'vue-events'
require('../../../bootstrap')
Vue.use(VueEvents)
Vue.use(VueRouter)
Vue.use(ElementUI, { locale })

if (window.i18.lang === 'de') {
  locale.use(de)
} else if (window.i18.lang === 'en') {
  locale.use(en)
} else {
  locale.use(fr)
}

Vue.prototype.$http = window.axios
Vue.prototype.trans = (key, options = {}) => {
  // eslint-disable-next-line
    Lang.setLocale(window.i18.lang)
  return Lang.get(key, options)
}

Vue.filter('str_limit', function (string, value) {
  return string.length <= parseInt(value) ? string : string.substring(0, value) + ' ...'
})

let $transactionsComponent = document.querySelector('#transactionsComponent')

if ($transactionsComponent) {
  const router = new VueRouter({
    routes: [{
      path: '/',
      name: 'root',
      component: require('./components/TransactionsComponent.vue'),
      children: [{
        path: '/edit/:id(\\d+)',
        name: 'root.edit',
        component: require('./components/EditTransactionComponent.vue')
      }]
    }]
  })
  new Vue({
    el: '#transactionsComponent',
    router,
    store,
    components: { },
    mounted () {
    },
    methods: {
    }
  })
}
