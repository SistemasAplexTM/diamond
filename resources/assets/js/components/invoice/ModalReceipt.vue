<template>
  <div>
    <el-dialog
      :visible.sync="dialogVisible"
      :modal-append-to-body="true"
      width="30%"
      @close="$emit('close')">
      <template slot="title">
        <h3><i class="el-icon-folder-opened"></i> Adjuntar Recibos</h3>
      </template>
      <el-row :gutter="24">
        <el-col :span="24">
          <el-autocomplete
            class="inline-input"
            v-model="receipt.name"
            :fetch-suggestions="querySearch"
            :trigger-on-focus="false"
            placeholder="Buscar Recibo"
            @select="handleSelect"
            size="medium"
            autocomplete="nuevo-recibo"
            clearable
          >
            <template slot-scope="{ item }">
              <div class="content-select">
                <div style>
                  <i class="fal fa-user icon"></i>
                  {{ item.consignee }}
                </div>
                <div style="color: #8492a6;">
                  <i class="fal fa-box-open icon"></i>
                  {{ item.name }} &nbsp;&nbsp;
                  <i class="fal fa-balance-scale icon"></i>
                  {{ item.peso }} Lb &nbsp;&nbsp;
                </div>
                <div style="color: #8492a6; font-size: 13px">
                  <div>
                    <i class="fal fa-truck icon"></i>
                    {{ item.tracking }}
                  </div>
                  <div class="content-search">
                    <div class="content-search-item">
                      <i class="fal fa-comment-edit icon"></i>
                      {{ item.contenido }}
                    </div>
                  </div>
                </div>
                <hr class="hr-search" />
              </div>
            </template>
          </el-autocomplete>
        </el-col>
      </el-row>

      <!-- TABLA PIVOT -->
      <el-row :gutter="24" class="mt-20">
        <el-col :span="24">
          <el-table
            :data="tableData"
            height="300"
            style="width: 100%"
            v-loading="loading"
            element-loading-text="Cargando..."
            element-loading-spinner="el-icon-loading"
            element-loading-background="rgba(245, 245, 245, 0.8)">
            <el-table-column
              prop="document.created_at"
              label="Fecha Recibo">
            </el-table-column>
            <el-table-column
              prop="document.num_warehouse"
              label="Recibo">
            </el-table-column>
            <el-table-column
            label="" width="50">
            <template slot-scope="scope">
              <el-tooltip content="Eliminar" placement="top">
                <el-button type="danger" size="mini" icon="el-icon-delete" circle
                  @click="handleDelete(scope.$index, scope.row)"></el-button>
              </el-tooltip>
            </template>
          </el-table-column>
          </el-table>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false"><i class="fal fa-times"></i> Cerrar</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: 'modalReceipt',
  props:['openModal', 'invoice_id'],
  data() {
    return {
      dialogVisible: false,
      receipt: {},
      tableData: [],
      loading:false
    }
  },
  watch: {
    openModal: function(value) {
      this.dialogVisible = value;
      this.getReceipt()
    },
  },
  methods: {
    querySearch(queryString, cb) {
      if (queryString.length > 3) {
        var me = this;
        axios
          .get("/documento/getDataSearchDocument/" + queryString +"/Warehouse")
          .then(function(response) {
            me.options = response.data.data;
            cb(me.options);
          })
          .catch(function(error) {
            console.log(error);
            toastr.warning("Error: -" + error);
          });
      }
    },
    handleSelect(item) {
      var data = {
        invoice_id : this.invoice_id,
        document_id : item.id,
      }
      this.saveRelationReceipt(data);
    },
    getReceipt(){
      let me = this;
      this.loading = true
      axios.get("invoice/getRelationReceipt/" + me.invoice_id)
        .then(function(response) {
          me.tableData = response.data
          me.loading = false
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
          me.loading = false
        });
    },
    saveRelationReceipt(data){
      let me = this
      axios
        .post("invoice/saveRelationReceipt", {data})
        .then(function(response) {
          if (response.data["code"] == 200) {
            me.getReceipt();
            toastr.options.closeButton = true;
            toastr.success("Registro creado correctamente.");
          } else {
            toastr.options.closeButton = true;
            toastr.error("Error: " + response.data["error"]);
          }
        })
        .catch(function(error) {
          alert("Ocurrió un error al intentar registrar");
        });
    },
    handleDelete(index, row) {
      let me = this
      swal({
        title: 'Atención!',
        text: "Desea eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          axios.get('invoice/destroyRelationReceipt/'+row.id).then(function(response) {
            me.getReceipt()
            toastr.success("Registro eliminado correctamente.");
            toastr.options.closeButton = true;
          }).catch(function(error) {
            alert("Ocurrió un error al intentar eliminar");
          });
        }
      });
      
    },
  }
}
</script>

<style lang="css" scoped>
  .mt-20{
    margin-top: 20px;
  }
  .content-search {
    overflow: hidden;
  }
  .content-search-item {
    float: left;
  }
  .hr-search {
    margin-bottom: 0px;
  }
  .content-select {
    padding-top: 10px;
  }
  .icon {
    font-size: 11px;
  }
  .content-select {
    padding-top: 7px;
    line-height: 17px;
  }
</style>