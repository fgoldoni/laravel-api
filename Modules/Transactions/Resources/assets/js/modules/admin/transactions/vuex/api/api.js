export default {
  loadTransactions (success, error) {
    return window.axios.get(window.laroute.route('admin.transactions.view')).then(
      (response) => success(response.data),
      (response) => error(response)
    )
  }
}
