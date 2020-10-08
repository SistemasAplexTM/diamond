<template>
  <div
    v-loading="loading"
    element-loading-text="Cargando..."
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(212, 212, 212, 0.8)">
    <el-row :gutter="24" class="row_form">
      <el-col :span="8">
        <span class="input-label">Fecha: </span>
        <el-date-picker
          v-model="date_document"
          type="date"
          placeholder="aaaa-mm-dd"
          format="yyyy-MM-dd"
          value-format="yyyy-MM-dd">
        </el-date-picker>
      </el-col>
      <el-col :span="8">
        <span class="input-label">Vencimiento: </span>
        <el-date-picker
          v-model="due_date"
          type="date"
          placeholder="aaaa-mm-dd"
          format="yyyy-MM-dd"
          value-format="yyyy-MM-dd">
        </el-date-picker>
      </el-col>
      <el-col :span="8">
        <span class="input-label">Moneda: </span>
        <el-select size="medium" 
          value-key="id" 
          v-model="currency_id" 
          clearable 
          placeholder="Seleccione">
          <el-option
            v-for="item in currencies"
            :key="item.id"
            :label="item.moneda"
            :value="item.id">
            <span style="float: left">{{ item.moneda }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">{{ item.simbolo }}</span>
          </el-option>
        </el-select>
      </el-col>
    </el-row>
    <el-row :gutter="24" class="row_form">
      <el-col :span="12">
        <span class="input-label">Cliente: <small>{{ client_id.table_name }}</small></span>
        <el-autocomplete
          class="inline-input"
          v-model="client_id.name"
          :fetch-suggestions="querySearch"
          :trigger-on-focus="false"
          placeholder="Buscar Cliente.."
          @select="handleSelect"
          size="medium"
          autocomplete="nuevo-cliente"
          clearable
        >
          <template slot-scope="{ item }">
            <div>
              <label class="value_item" style="width:100%;">
                <i class="fal fa-user"></i>
                {{ item.name }} 
                <small style="float:right;" 
                  :class="((item.table_name == 'shipper') ? 'color_s' : ((item.table_name == 'consignee') ? 'color_c' :'color_m'))">
                  <i class="fal fa-user-circle"></i> {{ item.table_name }}
                </small>
              </label>
            </div>
          </template>
        </el-autocomplete>
      </el-col>
      <el-col :span="12">
        <span class="input-label">Adjunte recibos a esta factura</span>
        <el-button type="success" size="medium" icon="el-icon-folder-opened" @click="attachReceipt()" style="width:100%">Clik para adjuntar</el-button>
      </el-col>
    </el-row>
    <div class="detail_txt">Detalle</div>
    <el-row :gutter="24" class="row_form">
      <el-col :span="14">
        <span class="input-label">Descripción</span>
        <el-input size="medium" type="textarea" :rows="4"  placeholder="Descripción" v-model="description"></el-input>
      </el-col>
      <el-col :span="7">
        <span class="input-label">Cantidad</span>
        <el-input size="medium" type="number" min="0" placeholder="0" v-model="quantity"></el-input>
        <span class="input-label">Valor</span>
        <el-input size="medium" type="number" min="0" placeholder="$00.00" v-model="amount"></el-input>
      </el-col>
      <el-col :span="3">
        <span class="input-label">&nbsp;</span>
        <el-tooltip class="item" effect="dark" content="Agregar" placement="top">
          <el-button type="primary" icon="el-icon-plus" circle @click="addDetail"></el-button>
        </el-tooltip>
      </el-col>
    </el-row>
    
    <!-- DETALLE -->
    <el-row class="mt-20">
      <el-col>
        <el-table
          :data="tableData"
          style="width: 100%"
          height="300"
          v-loading="loadingDetail"
          element-loading-text="Cargando..."
          element-loading-spinner="el-icon-loading"
          element-loading-background="rgba(212, 212, 212, 0.8)">
          <el-table-column
            label="Descripción"
            width="210"
            prop="description">
          </el-table-column>
          <el-table-column
            label="Cant."
            prop="quantity">
          </el-table-column>
          <el-table-column
            label="Valor"
            prop="amount">
            <template slot-scope="scope">
              <div>{{ formatPrice(scope.row.amount) }}</div>
            </template>
          </el-table-column>
          <el-table-column
            label="">
            <template slot-scope="scope">
              <el-tooltip content="Eliminar" placement="top">
                <el-button type="danger" size="mini" icon="el-icon-delete" circle
                  class="fr"
                  @click="handleDelete(scope.$index, scope.row)"></el-button>
              </el-tooltip>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
    <modal-receipt :openModal="openModal" :invoice_id="id" @close="openModal = false"></modal-receipt>
  </div>
</template>
<script>
import ModalReceipt from './ModalReceipt'
export default {
  name: 'invoice',
  props: ['payload'],
  components:{
    ModalReceipt
  },
  watch: {
    payload: {
      handler(nv, ov) {
        this.asignedData();
      },
      deep: true
    },
  },
  data(){
    return{
      id: '',
      invoice: null,
      agency_id: this.payload.agency.id,
      consecutive:'',
      date_document:this.getTime(),
      due_date:'',
      client_id:{},
      currency_id:1,
      observation:'',
      currencies:[],
      //detail
      description: '',
      quantity: 1,
      amount: '',
      tableData: [],
      loading: false,
      loadingDetail: false,
      error: false,
      openModal: false
    }
  },
  created() {
    let me = this;
    this.getCurrency();
    if (this.id == '') {
      this.asignedData();
    }
    bus.$on("save", function(payload) {
      me.beforeSend();
    });
    bus.$on("update", function(payload) {
      me.beforeSend(true);
    });
    bus.$on("cancel", function(payload) {
      me.resetForm();
    });
  },
  methods:{
    openRigthBar(edit) {
      var data = {
        component: 'invoice',
        title: (edit) ? 'Factura #' + this.id : 'Nueva Factura',
        icon: 'fal fa-file-invoice-dollar',
        table: 'invoice',
        hidden_btn: true,
        edit: edit,
        agency: this.payload.agency,
        invoice: this.invoice,
        client: this.client_id
      }
      bus.$emit('open', data)
    },
    attachReceipt(){
      if (this.id != '') {
        this.openModal=true
      }else{
        toastr.warning("Es necesario guardar la factura para adjuntar recibos.");
      }
    },
    asignedData(){
      let me = this;
      this.loading = true;
      setTimeout(() => {
        if (me.payload.edit) {
          me.id = me.payload.invoice.id;
          me.consecutive = me.payload.invoice.consecutive;
          me.date_document = me.payload.invoice.date_document;
          me.due_date = me.payload.invoice.due_date;
          me.client_id = {
            id: me.payload.client.id,
            name: me.payload.client.name,
            table_name: me.payload.invoice.client_table,
          },
          me.currency_id = parseInt(me.payload.invoice.currency_id);
          me.observation = me.payload.invoice.observation;
          me.getDetail();
        }else{
          if (me.payload.client) {
            me.client_id = {
              id: me.payload.client.id,
              name: me.payload.client.name,
              table_name: me.payload.client.table_name,
            }
          }
        }
        me.loading = false;
      }, 300);
    },
    beforeSend(edit){
      this.loading = true;
      if (this.validateFields()) {
        if (edit) {
          this.update();
        } else {
          this.store();
        }
      }else{
        this.loading = false;
      }
    },
    validateFields(){
      var op = true;
      if (this.date_document == '') {
        op = false; this.error = true;
        toastr.warning("Atención! Ingresa la fecha.");
      }else{
        if (this.currency_id == '') {
          op = false; this.error = true;
          toastr.warning("Atención! Ingresa la moneda.");
        }else{
          if (this.isEmpty(this.client_id)) {
            op = false; this.error = true;
            toastr.warning("Atención! Ingresa el cliente.");
          }
        }
      }
      return op;      
    },
    store(){
      var me = this;
      var data = {
        agency_id: this.agency_id,
        // consecutive: this.consecutive,
        date_document: this.date_document,
        due_date: this.due_date,
        client_table: this.client_id.table_name,
        client_id: this.client_id.id,
        currency_id: this.currency_id,
        observation: this.observation,
      }
      axios
        .post("invoice", {data})
        .then(function(response) {
          if (response.data["code"] == 200) {
            me.id = response.data.datos.id;
            me.invoice = response.data.datos;
            bus.$emit('refresh', me.invoice); // Refrescar tabla de facturas
            toastr.success("Registro creado correctamente.");
            toastr.options.closeButton = true;
            me.loading = false;
          } else {
            toastr.warning("Error");
            toastr.options.closeButton = true;
            me.loading = false;
          }
        })
        .catch(function(error) {
          alert("Ocurrió un error al intentar registrar");
          me.loading = false;
        });
    },
    update(){
      var me = this;
      var data = {
        agency_id: this.agency_id,
        // consecutive: this.consecutive,
        date_document: this.date_document,
        due_date: this.due_date,
        client_table: this.client_id.table_name,
        client_id: this.client_id.id,
        currency_id: this.currency_id,
        observation: this.observation,
      }
      axios
        .put("invoice/"+ this.id, {data})
        .then(function(response) {
          if (response.data["code"] == 200) {
            bus.$emit('refresh'); // Refrescar tabla de facturas
            toastr.success("Registro editado correctamente.");
            toastr.options.closeButton = true;
            me.loading = false;
          } else {
            toastr.warning("Error");
            toastr.options.closeButton = true;
            me.loading = false;
          }
        })
        .catch(function(error) {
          alert("Ocurrió un error al intentar editar");
          me.loading = false;
        });
    },
    addDetail(){
      let me = this;
      var data = {
        invoice_id: this.id,
        description: this.description,
        quantity: this.quantity,
        amount: this.amount,
      };
      if (this.id != '') {
        axios
        .post("invoice/createDetail", {data})
        .then(function(response) {
          if (response.data["code"] == 200) {
            // me.resetDetail();
            // me.getDetail();
            // bus.$emit('refresh'); // Refrescar tabla de facturas
            toastr.success("Detalle agregado.");
            toastr.options.closeButton = true;
            me.openRigthBar(true)
          } else {
            toastr.warning("Error");
            toastr.options.closeButton = true;
          }
        })
        .catch(function(error) {
          alert("Ocurrió un error al intentar registrar");
        });
      }else{
        me.beforeSend();
        if (!me.error) {
          setTimeout(() => {
            me.addDetail()
          }, 1000);
        } 
      }
    },
    getDetail(){
      let me = this;
      me.loadingDetail=true
      axios.get("invoice/getDetail/" + me.id)
        .then(function(response) {
          me.tableData = response.data
          me.loadingDetail=false
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
          me.loadingDetail=false
        });
    },
    querySearch(queryString, cb) {
      if (queryString.length > 3) {
        var me = this;
        axios
          .get("/invoice/getSelectClient/" + queryString)
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
      this.client_id = item;
    },
    getCurrency(){
      let me = this;
      axios.get("invoice/getCurrency")
        .then(function(response) {
          if (response.data.code === 200) {
            me.currencies = response.data.data
          } else {
            toastr.warning("Atención! ha ocurrido un error.");
          }
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
        });
    },
    isEmpty(obj) {
      for(var key in obj) {
        if(obj.hasOwnProperty(key))
          return false;
      }
      return true;
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
          axios.get('invoice/destroyDetail/'+row.id).then(function(response) {
            me.getDetail()
            bus.$emit('refresh'); // Refrescar tabla de facturas
            toastr.success("Registro eliminado correctamente.");
            toastr.options.closeButton = true;
          }).catch(function(error) {
            alert("Ocurrió un error al intentar eliminar");
          });
        }
      });
      
    },
    resetDetail(){
      this.description = '';
      this.quantity = 1;
      this.amount = '';
    },
    resetForm(){
      this.resetDetail();
      this.id = '';
      this.consecutive='',
      this.date_document='',
      this.client_id={},
      this.currency=1,
      this.tableData = [];
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2).replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },
  }
}
</script> 

<style lang="css" scoped>
.mt-20{
    margin-top: 20px;
  }
.color_s{
  color: red;
}
.color_c{
  color:blue;
}
.color_m{
  color: green;
}
.row_form{
  margin-top: 10px;
}
.input-label{
  font-size: 15px;
}
.detail_txt{
  border-bottom: 1px dashed;
  margin: 0 0 20px;
  padding: 20px 0 0;
  font-size: 20px;
  text-align: center;
}
  
</style>