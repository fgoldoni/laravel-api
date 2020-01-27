export default {
  table: {
    tableClass: 'table table-xs text-nowrap table-bordered table-striped',
    loadingClass: 'icon-spinner spinner',
    ascendingIcon: 'sorting_asc',
    descendingIcon: 'sorting_desc',
    sortableIcon: 'sorting',
    handleIcon: 'fa fa-search-plus',
    renderIcon: function (classes, options) {
      return `<span class="${classes.join(' ')}"></span>`
    },
    field: {
      titleClass: 'titleClass'
    }
  },
  paginationInfo: {
    infoClass: 'dataTables_info pull-left'
  },
  pagination: {
    wrapperClass: 'dataTables_paginate paging_simple_numbers pull-right',
    activeClass: 'paginate_button current',
    disabledClass: 'paginate_button',
    pageClass: 'paginate_button',
    linkClass: 'paginate_button',
    icons: {
      first: '',
      prev: '',
      next: '',
      last: ''
    }
  }
}
