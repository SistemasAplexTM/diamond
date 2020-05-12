function edit(id) {
  objVue.id_edit = id;
}
function deleteRecord(id) {
  objVue.id_delete = id;
}
var objVue = new Vue({
  el: '#invoice',
  data: {
      agency_data: data_agencia,
      id_edit: null,
      id_delete: null,
  },
  created(){
    let me = this
    bus.$on("close_form", function() {
      me.id_edit = null
    });
  }
})
