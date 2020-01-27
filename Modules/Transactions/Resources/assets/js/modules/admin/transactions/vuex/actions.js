import * as types from '../../../../vuex/mutation-types'

export const addCheckedId = function (store, id) {
  store.commit(types.ADD_CHECKED_ID, id)
}

export const removeCheckedId = function (store, id) {
  store.commit(types.REMOVE_CHECKED_ID, id)
}

export const addChecked = function (store, checked) {
  store.commit(types.ADD_CHECKED, checked)
}

export const addTransaction = function (store, transaction) {
  store.commit(types.ADD_TRANSACTION, transaction)
}
