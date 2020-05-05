<template lang="html">
  <div class="contentc">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Facturas</h5>
            <div class="ibox-tools">
                <a class="btn btn-primary" data-toggle="tooltip" title="Crear Factura" @click="openRigthBar"><span>Nueva <i class="fal fa-plus" style="font-size: small;"></i></span></a>
            </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="tbl-invoice" class="table table-striped table-hover table-bordered" style="width: 100%;">
              <thead>
                <tr>
                  <th><i class="fal fa-file-invoice-dollar"></i> Factura</th>
                  <th><i class="fal fa-calendar-alt"></i> Fecha</th>
                  <th><i class="fal fa-user"></i> Cliente</th>
                  <th><i class="fal fa-money-bill-wave"></i> Moneda</th>
                  <th><i class="fal fa-dollar-sign"></i> Monto Total</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['agency_data'],
  data(){
    return {
      id: 0
    }
  },
  mounted(){
    this.getAll();
  },
  methods:{
    openRigthBar() {
      var data = {
        component: 'invoice',
        title: 'Nueva Factura',
        icon: 'fal fa-file-invoice-dollar',
        table: 'invoice',
        hidden_btn: true,
        edit: false,
        agency: this.agency_data
      }
      bus.$emit('open', data)
    },
    getAll(){
      $('#tbl-invoice').DataTable({
          processing: true,
          serverSide: true,
          responsive: true,
          lengthMenu: [[40, 50, 80, 100, 200, -1], [40, 50, 80, 100, 200, "All"]],
          ajax: '/invoice/getAll',
          "order": [[ 0, "desc" ]],
          columns: [
              {data: 'consecutive', name: 'consecutive'},
              {data: 'date_document', name: 'date_document'},
              {data: 'client_id', name: 'client_id'},
              {data: 'currency', name: 'currency'},
              {
                sortable: false,
                "render": function (data, type, full, meta) {
                  var total = 0;
                  full.detail.forEach(el => {
                    total += parseFloat(el.amount) * parseInt(el.quantity);
                  });
                  return total;
                }
              },
              {
                  sortable: false,
                  "render": function (data, type, full, meta) {
                      var btn_edit = '';
                      var btn_delete = '';
                      var btn_edit = '<a href="#" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="fal fa-pencil fa-lg"></i></a> ';
                      var btn_pdf = '<a href="#" class="edit" title="Ver PDF" data-toggle="tooltip" style="color:#ff0740;"><i class="fal fa-file-pdf fa-lg"></i></a> ';
                      var btn_email = '<a href="#" class="edit" title="Enviar Email" data-toggle="tooltip" style="color:#075fff;"><i class="fal fa-envelope-open-text fa-lg"></i></a> ';
                      var btn_delete = ' <a class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="fal fa-trash-alt fa-lg"></i></a>';
                      return btn_edit + btn_pdf + btn_email + '&nbsp;' + btn_delete;
                  },
                  width:150
              }
          ]
      });
    }
  }
}
</script>

<style lang="css" scoped>

</style>
