<template>
  <div>
    <el-row :gutter="24" class="row_form">
      <el-col :span="12">
        <span class="input-label">Fecha: </span>
        <el-date-picker
          v-model="date_document"
          type="date"
          placeholder="aaaa-mm-dd"
          format="yyyy-MM-dd"
          value-format="yyyy-MM-dd">
        </el-date-picker>
      </el-col>
      <el-col :span="12">
        <span class="input-label">Moneda: </span>
        <el-select size="medium" value-key="id" v-model="currency" clearable placeholder="Seleccione">
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
      <el-col :span="24">
        <span class="input-label">Cliente: <small>{{ client_id.table_name }}</small></span>
        <el-autocomplete
          class="inline-input"
          v-model="client_id.name"
          :fetch-suggestions="querySearch"
          :trigger-on-focus="false"
          placeholder="Buscar Cliente"
          @select="handleSelect"
          size="medium"
          autocomplete="nuevo-cliente"
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
    </el-row>
    <div class="detail_txt">Detalle</div>
    <el-row :gutter="24" class="row_form">
      <el-col :span="9">
        <span class="input-label">Descripción</span>
        <el-input size="medium" type="textarea" :rows="4"  placeholder="Descripción" v-model="description"></el-input>
      </el-col>
      <el-col :span="5">
        <span class="input-label">Cant.</span>
        <el-input size="medium" type="number" placeholder="Seleccione" v-model="quantity"></el-input>
      </el-col>
      <el-col :span="7">
        <span class="input-label">Valor</span>
        <el-input size="medium" type="number" placeholder="Seleccione" v-model="amount"></el-input>
      </el-col>
      <el-col :span="3">
        <span class="input-label">&nbsp;</span>
        <el-tooltip class="item" effect="dark" content="Agregar" placement="top">
          <el-button type="primary" icon="el-icon-plus" circle @click="addDetail"></el-button>
        </el-tooltip>
      </el-col>
    </el-row>
    
    <!-- DETALLE -->
    <el-row>
      <el-col>
        <el-table
          :data="tableData"
          style="width: 100%"
          height="300">
          <el-table-column
            label="Descripción"
            width="200"
            prop="description">
          </el-table-column>
          <el-table-column
            label="Cantidad"
            prop="quantity">
          </el-table-column>
          <el-table-column
            label="Valor"
            prop="amount">
          </el-table-column>
          <el-table-column
            label="">
            <template slot-scope="scope">
              <el-button type="danger" size="mini" icon="el-icon-delete" circle
                class="fr"
                @click="handleDelete(scope.$index, scope.row)"></el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  </div>
</template>
<script>
export default {
  name: 'invoice',
  props: ['payload'],
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
      agency_id: this.payload.agency.id,
      consecutive:'',
      date_document:'',
      client_id:{},
      currency:1,
      observation:'',
      currencies:[],
      //detail
      description: '',
      quantity: 1,
      amount: '',
      tableData: [],
      loading: false,
      error: false
    }
  },
  mounted() {
    
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
    asignedData(){
      let me = this;
      setTimeout(() => {
        if (me.payload.edit) {
          me.id = me.payload.invoice.id;
          me.consecutive = me.payload.invoice.consecutive;
          me.date_document = me.payload.invoice.date_document;
          me.client_id = {
            id: me.payload.client.id,
            name: me.payload.client.name,
            table_name: me.payload.invoice.client_table,
          },
          me.currency = parseInt(me.payload.invoice.currency);
          me.observation = me.payload.invoice.observation;
          me.getDetail();
        }
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
      }
      this.loading = false;
    },
    validateFields(){
      var op = true;
      if (this.date_document == '') {
        op = false; this.error = true;
        toastr.warning("Atención! Ingresa la fecha.");
      }else{
        if (this.currency == '') {
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
        client_table: this.client_id.table_name,
        client_id: this.client_id.id,
        currency: this.currency,
        observation: this.observation,
      }
      axios
        .post("invoice", {data})
        .then(function(response) {
          if (response.data["code"] == 200) {
            me.id = response.data.datos.id;
            bus.$emit('refresh'); // Refrescar tabla de facturas
            toastr.success("Registro creado correctamente.");
            toastr.options.closeButton = true;
          } else {
            toastr.warning("Error");
            toastr.options.closeButton = true;
          }
        })
        .catch(function(error) {
          alert("Ocurrió un error al intentar registrar");
        });
    },
    update(){
      console.log('actualizar');
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
            me.resetDetail();
            me.getDetail();
            bus.$emit('refresh'); // Refrescar tabla de facturas
            toastr.success("Detalle agregado.");
            toastr.options.closeButton = true;
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
      axios.get("invoice/getDetail/" + me.id)
        .then(function(response) {
          me.tableData = response.data
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
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
    }
  }
}
</script>

<style lang="css" scoped>
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