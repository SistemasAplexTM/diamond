<template lang="html">
  <div class="contentc">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Facturas</h5>
            <div class="ibox-tools">
                <a class="btn btn-primary" data-toggle="tooltip" title="Crear Factura" @click="openRigthBar(false)"><span>Nueva <i class="fal fa-plus" style="font-size: small;"></i></span></a>
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
                  <th><i class="fal fa-warehouse-alt"></i> Agencia</th>
                  <th><i class="fal fa-bolt"></i> Acciones</th>
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
  props: ['agency_data', 'id_edit', 'id_delete'],
  watch: {
    id_edit:function(value){
      if (value != null) {
        this.id = value
        this.edit();
      }
    },
    id_delete:function(value){
      if (value != null) {
        this.delete(value);
      }
    },
  },
  data(){
    return {
      id: null,
      data_edit: null,
      client : null
    }
  },
  created() {
    let me = this;
    bus.$on("refresh", function(payload) {
      me.id = payload.id;
      me.getAll();
    });
    bus.$on("pdf", function(payload) {
      if(me.id !== null){
        window.open('invoice/pdf/'+me.id);
      }
    });
    bus.$on("email", function(payload) {
      console.log('email index');
    });
  },
  mounted(){
    this.getAll();
  },
  methods:{
    edit(){
      let me = this;
      axios.get("invoice/getInvoiceById/" + me.id)
        .then(function(response) {
          me.data_edit = response.data.invoice;
          me.client = response.data.client;
          me.openRigthBar(true);
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
        });
    },
    openRigthBar(edit) {
      var data = {
        component: 'invoice',
        title: (edit) ? 'Factura #' + this.id : 'Nueva Factura',
        icon: 'fal fa-file-invoice-dollar',
        table: 'invoice',
        hidden_btn: true,
        edit: edit,
        agency: this.agency_data,
        invoice: this.data_edit,
        client: this.client
      }
      bus.$emit('open', data)
    },
    getAll(){
      if ($.fn.DataTable.isDataTable('#tbl-invoice')) {
        var table = $('#tbl-invoice').DataTable();
        table.clear();
        $('#tbl-invoice').dataTable().fnDestroy();
        $('#tbl-invoice tbody').empty();
      }
      $('#tbl-invoice').DataTable({
          processing: true,
          serverSide: true,
          responsive: true,
          lengthMenu: [[40, 50, 80, 100, 200, -1], [40, 50, 80, 100, 200, "All"]],
          ajax: '/invoice/getAll',
          "order": [[ 0, "desc" ]],
          columns: [
              {data: 'id', name: 'id'},
              {data: 'date_document', name: 'date_document'},
              {
                "render": function (data, type, full, meta) {
                  return full.client_id.name;
                },
                name: 'client_id.name'
              },
              {
                sortable: false,
                "render": function (data, type, full, meta) {
                  return '('+full.currency.moneda+') ' + full.currency.descripcion;
                }
              },
              {
                sortable: false,
                "render": function (data, type, full, meta) {
                  var total = 0;
                  full.detail.forEach(el => {
                    total += parseFloat(el.amount) * parseInt(el.quantity);
                  });
                  return full.currency.simbolo + ' ' + objVue.formatPrice(total);
                }
              },
              {
                "render": function (data, type, full, meta) {
                  return full.agency.descripcion;
                },
                name: 'agency.descripcion'
              },
              {
                  sortable: false,
                  "render": function (data, type, full, meta) {
                      var btn_edit = '';
                      var btn_delete = '';
                      var btn_edit = '<a onclick="edit('+full.id+')" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="fal fa-pencil fa-lg"></i></a> ';
                      var btn_pdf = '<a href="invoice/pdf/'+full.id+'" target="blank_" class="edit" title="Ver PDF" data-toggle="tooltip" style="color:#ff0740;"><i class="fal fa-file-pdf fa-lg"></i></a> ';
                      var btn_email = '<a href="#" class="edit" title="Enviar Email" data-toggle="tooltip" style="color:#075fff;"><i class="fal fa-envelope-open-text fa-lg"></i></a> ';
                      var btn_delete = ' <a onclick="deleteRecord('+full.id+')" class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="fal fa-trash-alt fa-lg"></i></a>';
                      return btn_edit + btn_pdf + btn_email + '&nbsp;' + btn_delete;
                  },
                  width:150
              }
          ]
      });
    },
    delete(id){
      let me = this
      swal({
        title: 'Atención!',
        text: "Desea eliminar la factura #"+id+"?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          axios.delete('invoice/'+id).then(function(response) {
            me.getAll()
            bus.$emit('refresh'); // Refrescar tabla de facturas
            toastr.success("Registro eliminado correctamente.");
            toastr.options.closeButton = true;
          }).catch(function(error) {
            alert("Ocurrió un error al intentar eliminar");
          });
        }
      });
    }
  }
}
</script>

<style lang="css" scoped>

</style>
