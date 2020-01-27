export default [
  {
    name: '__checkbox'
  },
  {
    name: '__component:custom-link-by-id',
    title: window.Lang.get('tables.id'),
    sortField: 'id',
    visible: true
  },
  {
    name: 'code',
    title: window.Lang.get('tables.code'),
    sortField: 'code',
    visible: true
  },
  {
    name: 'method',
    title: window.Lang.get('tables.method'),
    sortField: 'method',
    visible: true
  },
  {
    name: 'amount',
    title: window.Lang.get('tables.amount'),
    sortField: 'amount',
    callback: 'formatNumber',
    visible: true
  },
  {
    name: 'user.full_name',
    title: window.Lang.get('tables.full_name'),
    visible: true
  },
  {
    name: 'user.email',
    title: window.Lang.get('tables.email'),
    visible: true
  },
  {
    name: 'created_at',
    title: window.Lang.get('tables.created_at'),
    sortField: 'created_at',
    callback: 'formatDate|DD, MMM YYYY HH:mm',
    visible: false
  },
  {
    name: 'updated_at',
    title: window.Lang.get('tables.updated_at'),
    sortField: 'updated_at',
    callback: 'formatDate|DD, MMM YYYY HH:mm',
    visible: false
  },

  {
    name: '__component:custom-actions',
    title: 'Actions',
    titleClass: 'text-center',
    dataClass: 'text-center',
    visible: true
  }
]
