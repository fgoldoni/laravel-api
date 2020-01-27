export default {
  loadUser (success, error) {
    return window.axios.get(window.laroute.route('users.current')).then(
      (response) => success(response.data),
      (response) => error(response)
    )
  },
  loadLanguages (success, error) {
    return window.axios.get(window.laroute.route('languages')).then(
      (response) => success(response.data),
      (response) => error(response)
    )
  }
}
