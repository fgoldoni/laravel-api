import api from './api/api'

export const loadLoggedUser = function (store) {
  api.loadUser(response => {
    if (!response) {
      console.log('error', response)
      return
    }

    store.commit('LOGIN_USER', response.user)
  }, error => {
    console.log('LOGIN_USER not answer', error)
  })
}

export const loadLanguages = function (store) {
  api.loadLanguages(response => {
    if (!response) {
      console.log('error', response)
      return
    }

    store.commit('LOAD_LANGUAGES', response.languages)
  }, error => {
    console.log('LOAD_LANGUAGES not answer', error)
  })
}

export const block = function (store, {element, load}) {
  if (load) {
    $('#' + element).find('div.table-responsive').block({
      message: '<i class="icon-spinner2 spinner"></i>',
      overlayCSS: {
        backgroundColor: '#fff',
        opacity: 0.8,
        cursor: 'wait',
        'box-shadow': '0 0 0 1px #ddd'
      },
      css: {
        border: 0,
        padding: 0,
        backgroundColor: 'none'
      }
    })
  } else {
    $('#' + element).find('div.table-responsive').unblock()
  }
}
