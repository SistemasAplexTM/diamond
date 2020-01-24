<template>
  <div>
    <el-dialog
      :title="'Contactos ' + dataRequest.option"
      :visible.sync="dialogTableVisible"
      :append-to-body="true"
      :height="250"
      style="width: 100%"
    >
      <h3>Agregar contacto existente</h3>
      <existing-contact
        :id_data="dataRequest.id_data"
        :table="dataRequest.option"
        @updatetable="updateTable"
      />
      <el-table :data="gridData">
        <span slot="empty">No hay datos :(</span>

        <el-table-column property="nombre_full" label="Nombre"></el-table-column>
        <!-- <el-table-column property="telefono" label="Teléfono"></el-table-column> -->
        <el-table-column property="city.nombre" label="Ciudad"></el-table-column>
        <!-- <el-table-column property="zip" label="Zip"></el-table-column> -->
        <el-table-column label="Acciones" width="250">
          <template slot-scope="scope">
            <el-button size="mini" type="success" @click="selectData(scope.$index, scope.row)">
              <i class="fal fa-check"></i> Seleccionar
            </el-button>
            <el-button size="mini" type="danger" @click="removeContact(scope.$index, scope.row)">
              <i class="fal fa-trash"></i>
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>
<script>
import ExistingContact from "../contact/Existing";
export default {
  components: { ExistingContact },
  props: {
    open_modal: {
      type: Object,
      required: true
    }
  },
  watch: {
    open_modal: function(val) {
      this.dataRequest = val;
      if (val.open) {
        this.getData(val);
      }
    }
  },
  data() {
    return {
      gridData: [],
      dialogTableVisible: false,
      dataRequest: {}
    };
  },
  methods: {
    removeContact(index, row) {
      swal({
        title: "Seguro que desea eliminar este contacto?",
        // text: "No lo podrás recuperar después!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No, cancelar!"
      }).then(result => {
        if (result.value) {
          axios
            .get(`/${this.dataRequest.option}/removeContact/${row.id}`)
            .then(response => {
              this.updateTable();
              // toastr.success("Registro eliminado correctamente.");
            })
            .catch(error => {
              console.log(error);
            });
        }
      });
    },
    updateTable() {
      this.gridData = [];
      this.getData(this.dataRequest);
    },
    async getData(request) {
      try {
        let data = await axios.get(
          `/${request.option}/getContacts/${request.id_data}`
        );
        this.gridData = data.data;
        this.dialogTableVisible = true;
      } catch (error) {
        console.error(error);
      }
    },
    async selectData(index, row) {
      let me = this;
      try {
        axios
          .post("createContactsConsolidadoDetalle", {
            id_change: row.id,
            data: me.open_modal
          })
          .then(function(response) {
            toastr.success("Cambio Exitoso.");
            me.dialogTableVisible = false;
            var table = $("#tbl-consolidado").DataTable();
            table.ajax.reload();
          })
          .catch(function(error) {
            console.log(error);
            toastr.warning("Error.");
            toastr.options.closeButton = true;
          });
      } catch (error) {
        console.error(error);
      }
    }
  }
};
</script>
<style lang="css">
.el-table__empty-block {
  height: 300px;
}
</style>