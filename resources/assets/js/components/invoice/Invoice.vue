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
            :value="item">
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
          style="width: 100%">
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
  data(){
    return{
      id: '',
      agency_id: this.payload.agency.id,
      consecutive:'',
      date_document:'',
      client_table:'',
      client_id_table:'',
      client_id:{},
      currency:{
        codigo: "170",
        descripcion: "Colombian Peso",
        id: 1,
        moneda: "COP",
        simbolo: "$"
      },
      observation:'',
      currencies:[],
      //detail
      description: '',
      quantity: 1,
      amount: '',
      tableData: [],
      loading: false
    }
  },
  created() {
    this.getCurrency();
    let me = this;
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
    beforeSend(edit){
      this.loading = true;
      if (this.validateFields()) {
        // this.setFormToClient();
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
        op = false;
        toastr.warning("Atención! Ingresa la fecha.");
      }else{
        if (this.currency == '') {
          op = false;
          toastr.warning("Atención! Ingresa la moneda.");
        }else{
          if (this.isEmpty(this.client_id)) {
            op = false;
            toastr.warning("Atención! Ingresa el cliente.");
          }
        }
      }
      return op;      
    },
    store(){
      var data = {
        agency_id: this.agency_id,
        consecutive: this.consecutive,
        date_document: this.date_document,
        client_table: this.client_table,
        client_id: this.client_id,
        currency: this.currency,
        observation: this.observation,
      }
      console.log('guardar');
    },
    update(){
      console.log('actualizar');
    },
    addDetail(){
      this.tableData.push({
        description: this.description,
        quantity: this.quantity,
        amount: this.amount,
      });
      this.resetDetail();
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
      console.log(index, row);
    },
    resetDetail(){
      this.description = '';
      this.quantity = 1;
      this.amount = '';
    },
    resetForm(){
      this.resetDetail();
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