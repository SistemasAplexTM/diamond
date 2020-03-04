<style type="text/css">
span.error {
  color: #e74c3c;
  font-size: 20px;
  display: flex;
  justify-content: center;
}
.el-date-editor {
  width: auto !important;
}
</style>
<template>
  <form-wizard
    @on-complete="onComplete"
    @on-loading="setLoading"
    @on-validate="handleValidation"
    @on-error="handleErrorMessage"
    shape="circle"
    color="#1ab394"
    error-color="#ff4949"
    title
    subtitle
    back-button-text="Anterior"
    next-button-text="Siguiente"
    finish-button-text="Terminar"
  >
    <transition name="fade">
      <div class="row" v-if="num_master">
        <div class="col-lg-12">
          <div class="widget style1" style="background-color: rgb(26, 179, 148); color: white">
            <div class="row vertical-align">
              <div class="col-xs-3">
                <i class="fal fa-barcode fa-3x"></i>
              </div>
              <div class="col-xs-9 text-right">
                <h2 class="font-bold">{{ num_master }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div class="row" v-if="msg">
        <div class="col-lg-12">
          <div class="widget style1" style="background-color: rgb(26, 179, 148); color: white">
            <div class="row vertical-align">
              <div class="col-xs-3">
                <i class="fal fa-exclamation-triangle fa-3x"></i>
              </div>
              <div class="col-xs-9 text-center" v-if="msg != null">
                <h3 class="font-bold">{{ msg }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <tab-content title="Datos de envío" icon="fal fa-user" :before-change="validar_primer_tab">
      <div class="row">
        <div class="col-lg-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              Shipper's Name and Address
              <button
                @click="open('s', false)"
                class="btn btn-xs btn-primary btn-action pull-right"
                type="button"
              >
                <i class="fal fa-user-plus"></i>&nbsp;
              </button>
              <button
                @click="open('s', true)"
                class="btn btn-xs btn-warning btn-action pull-right mr-10"
                type="button"
                style="margin-right: 10px"
              >
                <i class="fal fa-edit"></i>&nbsp;
              </button>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group" :class="{'has-error': errors.has('nombre') }">
                    <label for="nombre">Nombre</label>
                    <!-- <p>{{ shipper.name }}</p> -->
                    <autocomplete-component
                      v-validate="'required'"
                      name="nombre"
                      :selection="shipper"
                      v-model="shipper.name"
                      type="s"
                      @change-select="setData"
                      url="master/buscar"
                    ></autocomplete-component>
                    <small
                      v-show="errors.has('nombre')"
                      class="bg-danger"
                    >{{ errors.first('nombre') }}</small>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="datos_shipper">Datos</label>
                    <div class="input-group" style="width:100%;">
                      <textarea
                        name="datos_shipper"
                        v-model="datos_shipper"
                        rows="6"
                        style="width:100%;resize:none;"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              Consignee's Name and Address
              <button
                @click="open('c', false)"
                class="btn btn-xs btn-primary btn-action pull-right"
                type="button"
              >
                <i class="fal fa-user-plus"></i>&nbsp;
              </button>
              <button
                @click="open('c', true)"
                class="btn btn-xs btn-warning btn-action pull-right mr-10"
                type="button"
                style="margin-right: 10px"
              >
                <i class="fal fa-edit"></i>&nbsp;
              </button>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group" :class="{'has-error': errors.has('nombreC') }">
                    <label for="nombre">Nombre</label>
                    <autocomplete-component
                      v-validate="'required'"
                      name="nombreC"
                      :selection="consignee"
                      v-model="consignee.name"
                      type="c"
                      @change-select="setData"
                      url="master/buscar"
                    ></autocomplete-component>
                    <small
                      v-show="errors.has('nombreC')"
                      class="bg-danger"
                    >{{ errors.first('nombreC') }}</small>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="datos_consignee">Datos</label>
                    <div class="input-group" style="width:100%;">
                      <textarea
                        name="datos_consignee"
                        v-model="datos_consignee"
                        rows="6"
                        style="width:100%;resize:none;"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              Issuing Carrier's Agent Name and City
              <button
                @click="open('cr', false)"
                class="btn btn-xs btn-primary btn-action pull-right"
                type="button"
              >
                <i class="fal fa-user-plus"></i>&nbsp;
              </button>
              <button
                @click="open('cr', true)"
                class="btn btn-xs btn-warning btn-action pull-right mr-10"
                type="button"
                style="margin-right: 10px"
              >
                <i class="fal fa-edit"></i>&nbsp;
              </button>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group" :class="{'has-error': errors.has('nombreCR') }">
                    <label for="nombreCR">Nombre</label>
                    <autocomplete-component
                      v-validate="'required'"
                      name="nombreCR"
                      :selection="carrier"
                      v-model="carrier.name"
                      type="cr"
                      @change-select="setData"
                      url="master/buscar"
                    ></autocomplete-component>
                    <small
                      v-show="errors.has('nombreCR')"
                      class="bg-danger"
                    >{{ errors.first('nombreCR') }}</small>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="datos_carrier">Datos</label>
                    <div class="input-group" style="width:100%;">
                      <textarea
                        name="datos_carrier"
                        v-model="datos_carrier"
                        rows="6"
                        style="width:100%;resize:none;"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </tab-content>
    <tab-content title="Aerolinea" icon="fal fa-plane" :before-change="validar_segundo_tab">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">Air Waybill</div>
            <div class="panel-body">
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="aerolinea">
                        Issued by:
                        <!-- <span v-if="editing">{{ aerolinea }}</span> -->
                      </label>
                      <el-select
                        v-model="aerolinea"
                        clearable
                        filterable
                        placeholder="Seleccione"
                        :disabled="disableAerolinea"
                        @change="getAerolineasInventario"
                        :class="{'has-error': errors.has('aerolinea') }"
                        value-key="id"
                        size="medium"
                      >
                        <el-option
                          v-for="item in aerolineas"
                          name="aerolinea"
                          :key="item.id"
                          :label="item.name"
                          :value="item"
                        >
                          <div>
                            <i class="fal fa-plane-alt" style="text-align: center;width: 25px;"></i>
                            {{ item.name }}
                          </div>
                        </el-option>
                      </el-select>
                      <small
                        v-show="errors.has('aerolinea')"
                        class="bg-danger"
                      >{{ errors.first('aerolinea') }}</small>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div
                      class="form-group"
                      :class="{'has-error': errors.has('aerolinea_inventario') }"
                    >
                      <label for="aerolinea_inventario">
                        Inventory:
                        <!-- <span v-if="editing">{{ aerolinea_inventario }}</span> -->
                      </label>
                      <a
                        style="float:right;color:dodgerblue;"
                        data-toggle="tooltip"
                        :title="icon_title"
                        :data-original-title="icon_title"
                        @click="write = !write"
                      >
                        <i :class="icon_cost"></i>
                      </a>
                      <el-select
                        v-show="!write"
                        v-validate="'required'"
                        v-model="aerolinea_inventario_id"
                        clearable
                        filterable
                        placeholder="Seleccione"
                        :disabled="disableAerolinea"
                        @change="setNumMaster"
                        value-key="id"
                        size="medium"
                      >
                        <el-option
                          v-for="item in aerolineas_inventario"
                          name="aerolinea"
                          :key="item.id"
                          :label="item.nombre"
                          :value="item"
                        >
                          <div>
                            <i class="fal fa-barcode-alt" style="text-align: center;width: 25px;"></i>
                            {{ item.nombre }}
                          </div>
                        </el-option>
                      </el-select>
                      <el-input
                        placeholder="Ingrese el Numero de Guía"
                        prefix-icon="el-icon-edit"
                        v-model="num_master"
                        size="medium"
                        v-show="write"
                      ></el-input>
                      <small
                        v-show="errors.has('aerolinea_inventario')"
                        class="bg-danger"
                      >{{ errors.first('aerolinea_inventario') }}</small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="agent_iata_data">Agent's IATA code</label>
                      <input
                        v-model="agent_iata_data"
                        class="form-control"
                        name
                        id="agent_iata_data"
                      />
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="num_account">Account No.</label>
                      <input
                        v-model="num_account"
                        class="form-control"
                        name="num_account"
                        id="num_account"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label
                        for="aeropuerto_salida"
                      >Airport of Departure(Address of first Carrier) and requested Routing</label>
                      <el-select
                        v-validate="'required'"
                        v-model="aeropuerto_salida"
                        clearable
                        filterable
                        placeholder="Seleccione"
                        :class="{'has-error': errors.has('aeropuerto_salida') }"
                        value-key="id"
                        size="medium"
                      >
                        <el-option
                          v-for="item in aeropuertos"
                          name="aerolinea"
                          :key="item.id"
                          :label="item.name"
                          :value="item"
                        >
                          <div>
                            <i
                              class="fal fa-plane-departure"
                              style="text-align: center;width: 25px;"
                            ></i>
                            {{ item.name }}
                          </div>
                        </el-option>
                      </el-select>
                      <small
                        v-show="errors.has('aeropuerto_salida')"
                        class="bg-danger"
                      >{{ errors.first('aeropuerto_salida') }}</small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div
                      class="form-group"
                      :class="{'has-error': errors.has('aeropuerto_destino') }"
                    >
                      <label
                        for="aeropuerto_destino"
                      >Airport of Destination(Address of first Carrier) and requested Routing</label>

                      <el-select
                        v-validate="'required'"
                        v-model="aeropuerto_destino"
                        clearable
                        filterable
                        placeholder="Seleccione"
                        :class="{'has-error': errors.has('aeropuerto_destino') }"
                        value-key="id"
                        size="medium"
                      >
                        <el-option
                          v-for="item in aeropuertos"
                          name="aerolinea"
                          :key="item.id"
                          :label="item.name"
                          :value="item"
                        >
                          <div>
                            <i class="fal fa-plane-arrival" style="text-align: center;width: 25px;"></i>
                            {{ item.name }}
                          </div>
                        </el-option>
                      </el-select>
                      <small
                        v-show="errors.has('aeropuerto_destino')"
                        class="bg-danger"
                      >{{ errors.first('aeropuerto_destino') }}</small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-lg-12">
                    <div
                      class="form-group"
                      :class="{'has-error': errors.has('account_information') }"
                    >
                      <label for="account_information">Account information</label>
                      <textarea
                        v-validate="'required'"
                        v-model="account_information"
                        class="form-control"
                        name="account_information"
                        id="account_information"
                        cols="30"
                        rows="5"
                      >
                        -PREPAID
                      </textarea>
                      <small
                        v-show="errors.has('account_information')"
                        class="bg-danger"
                      >{{ errors.first('account_information') }}</small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="reference_num">Reference number</label>
                      <input
                        v-model="reference_num"
                        class="form-control"
                        name="reference_num"
                        id="reference_num"
                      />
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="optional_shipping_info">Optional shipping inf.</label>
                      <input
                        v-model="optional_shipping_info"
                        id="optional_shipping_info"
                        class="form-control"
                        name="optional_shipping_info"
                      />
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="amount_insurance">Amount insurance</label>
                      <input
                        type="text"
                        v-model="amount_insurance"
                        id="amount_insurance"
                        class="form-control"
                        name="amount_insurance"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group" :class="{'has-error': errors.has('fecha_vuelo') }">
                      <label for="fecha_vuelo">Date of flight</label>
                      <el-date-picker
                        v-model="fecha_vuelo"
                        v-validate="'required'"
                        type="date"
                        placeholder="Seleccione"
                        value-format="yyyy-MM-dd"
                        name="fecha_vuelo"
                        size="medium"
                      ></el-date-picker>
                    </div>
                    <small
                      v-show="errors.has('fecha_vuelo')"
                      class="bg-danger"
                    >{{ errors.first('fecha_vuelo') }}</small>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group" :class="{'has-error': errors.has('currency') }">
                      <label for="currency">Currency</label>
                      <input
                        v-validate="'required'"
                        id="currency"
                        v-model="currency"
                        class="form-control"
                        name="currency"
                      />
                      <small
                        v-show="errors.has('currency')"
                        class="bg-danger"
                      >{{ errors.first('currency') }}</small>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group" :class="{'has-error': errors.has('chgs') }">
                      <label for="chgs">chgs</label>
                      <select
                        v-model="chgs"
                        v-validate="'required'"
                        name="chgs"
                        id="chgs"
                        class="form-control"
                      >
                        <option value="PP">PP</option>
                        <option value="CLL">CLL</option>
                      </select>
                      <small
                        v-show="errors.has('chgs')"
                        class="bg-danger"
                      >{{ errors.first('chgs') }}</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </tab-content>
    <tab-content title="Detalle" icon="fal fa-check" :before-change="validar_tercer_tab">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">Air Waybill</div>
            <div class="panel-body">
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-lg-6">
                    <div
                      class="form-group"
                      :class="{'has-error': errors.has('handing_information') }"
                    >
                      <label for="handing_information">Handing information</label>
                      <textarea
                        v-model="handing_information"
                        class="form-control"
                        name="handing_information"
                        id="handing_information"
                        cols="30"
                        rows="4"
                      ></textarea>
                      <small
                        v-show="errors.has('handing_information')"
                        class="bg-danger"
                      >{{ errors.first('handing_information') }}</small>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="handing_information">Master Detail</label>
                      <textarea v-model="master_detail" class="form-control" cols="30" rows="4"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <el-button
                    type="primary"
                    size="mini"
                    round
                    style="float: right;"
                    data-toggle="tooltip"
                    title="Agregar nueva fila"
                    @click="addRowDetail"
                  >
                    <i class="fal fa-plus"></i>
                    Agregar fila
                  </el-button>
                </div>
                <div class="row">
                  <div class="table-responsive">
                    <table class="table table-stripped">
                      <thead>
                        <tr>
                          <th>N° of Pieces RCP</th>
                          <th width="15%">Gross Weigth</th>
                          <th width="9%">Rate Class</th>
                          <th>Chargeable Weigth</th>
                          <th>Rate Charge</th>
                          <th>Total</th>
                          <th width="40%">Nature and Quantity of Goods(Incl. Dimensions or Volume)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(detail, index) in detail_data" v-bind:key="detail.id">
                          <td>
                            <div class="form-group" :class="{'has-error': errors.has('piezas') }">
                              <input
                                v-validate="'required'"
                                name="piezas"
                                v-model="detail.piezas"
                                type="number"
                                class="form-control"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="form-group" :class="{'has-error': errors.has('peso') }">
                              <div class="input-group">
                                <input
                                  v-validate="'required'"
                                  name="peso"
                                  v-model="detail.peso_kl"
                                  type="number"
                                  class="form-control"
                                  placeholder="Kl"
                                />
                                <span class="input-group-btn">
                                  <select
                                    class="btn"
                                    name="unidad_medida"
                                    v-model="detail.unidad_medida"
                                  >
                                    <option value="Kl">Kl</option>
                                    <!-- <option value="Lb">Lb</option> -->
                                  </select>
                                </span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="text"
                                class="form-control"
                                name="rate_class"
                                v-model="detail.rate_class"
                              />
                              <!-- <select class="form-control" name="rate_class" v-model="rate_class">
                                    <option value=""></option>
                                    <option value="M">M</option>
                                    <option value="N">N</option>
                                    <option value="Q">Q</option>
                                    <option value="B">B</option>
                                    <option value="K">K</option>
                                    <option value="C">C</option>
                                    <option value="R">R</option>
                                    <option value="S">S</option>
                                    <option value="U">U</option>
                                    <option value="E">E</option>
                                    <option value="X">X</option>
                                    <option value="Y">Y</option>
                                    <option value="Z">Z</option>
                              </select>-->
                            </div>
                          </td>
                          <td>
                            <div
                              class="form-group"
                              :class="{'has-error': errors.has('peso_cobrado') }"
                            >
                              <input
                                v-validate="'required'"
                                name="peso_cobrado"
                                v-model="detail.peso_cobrado"
                                type="number"
                                class="form-control"
                                @keyup="calculateTotal(index)"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="form-group" :class="{'has-error': errors.has('tarifa') }">
                              <div class="input-group">
                                <input
                                  v-validate="{ rules: { required: !this.min} }"
                                  name="tarifa"
                                  v-model="detail.tarifa"
                                  type="number"
                                  class="form-control"
                                  @keyup="calculateTotal(index)"
                                />
                                <span
                                  class="input-group-addon"
                                  data-toggle="tooltip"
                                  data-placement="top"
                                  title="MIN"
                                >
                                  <i
                                    class="fal fa-check"
                                    @click.prevent="detail.minima = true;"
                                    v-show="!detail.minima"
                                  ></i>
                                  <i
                                    class="fal fa-times"
                                    @click.prevent="detail.minima = false;"
                                    v-show="detail.minima"
                                  ></i>
                                </span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group" :class="{'has-error': errors.has('total') }">
                              <input
                                v-validate="'required'"
                                name="total"
                                v-model="detail.total"
                                type="number"
                                class="form-control"
                                :readonly="!detail.minima"
                              />
                            </div>
                          </td>
                          <td>
                            <div
                              class="form-group"
                              :class="{'has-error': errors.has('descripcion') }"
                            >
                              <textarea
                                v-validate="'required'"
                                name="descripcion"
                                v-model="detail.descripcion"
                                class="form-control"
                                cols="30"
                                rows="5"
                              ></textarea>
                            </div>
                            <label
                              style="color:#ff380b;cursor:pointer;font-size: 12px;"
                              class="pull-right"
                              data-toggle="tooltip"
                              title="Eliminar"
                              @click="deleteRowDetail(index)"
                            >
                              Eliminar Fila
                              <i class="fal fa-trash-alt"></i>
                            </label>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label for="agent_iata_data">Total Other Charges Due Agent</label>
                      <input
                        v-model="total_other_charge_due_agent"
                        class="form-control"
                        name
                        id="total_other_charge_due_agent"
                        type="number"
                        readonly
                      />
                      <br />
                      <label for="agent_iata_data">Total Other Charges Due Carrier</label>
                      <input
                        v-model="total_other_charge_due_carrier"
                        class="form-control"
                        name
                        id="total_other_charge_due_carrier"
                        type="number"
                        readonly
                      />
                    </div>
                  </div>

                  <div class="col-lg-9">
                    <div class="row" style="margin-bottom: 5px;">
                      <label>Other charges</label>
                      <el-button
                        class="pull-right"
                        type="primary"
                        size="mini"
                        round
                        data-toggle="tooltip"
                        title="Agregar nueva fila"
                        @click="addOtherChargue()"
                      >
                        <i class="fal fa-plus"></i>
                        Agregar fila
                      </el-button>
                    </div>
                    <!-- <a class="pull-right">Add Row</a> -->
                    <div class="row">
                      <div class="table-responsive">
                        <table class="table table-stripped table-hover table-bordered" width="100">
                          <thead>
                            <tr>
                              <th rowspan="2" class="text-center" style="width: 60%;">Descripction</th>
                              <th colspan="2" class="text-center">Due</th>
                              <th rowspan="2" class="text-center">Amount</th>
                              <th rowspan="2"></th>
                            </tr>
                            <tr>
                              <th class="text-center">Agent</th>
                              <th class="text-center">Carrier</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(find, index) in other_c" :key="index">
                              <td>
                                <div class :class="{'has-error': errors.has('oc_description') }">
                                  <input
                                    type="text"
                                    class="form-control"
                                    name="oc_description"
                                    v-model="find.oc_description"
                                    v-validate="'required'"
                                  />
                                </div>
                              </td>
                              <td class="text-center">
                                <div class="radio radio-info">
                                  <input
                                    type="radio"
                                    value="0"
                                    :name="'agent'+index"
                                    aria-label="Single radio Two"
                                    v-model="find.oc_due"
                                    v-on:change="setDueAgent()"
                                  />
                                  <label></label>
                                </div>
                              </td>
                              <td class="text-center">
                                <div class="radio radio-info">
                                  <input
                                    type="radio"
                                    value="1"
                                    :name="'carrier'+index"
                                    aria-label="Single radio Two"
                                    v-model="find.oc_due"
                                    v-on:change="setDueAgent()"
                                  />
                                  <label></label>
                                </div>
                              </td>
                              <td>
                                <input
                                  type="number"
                                  class="form-control"
                                  v-model="find.oc_value"
                                  v-on:keyup="setDueAgent()"
                                />
                              </td>
                              <td style="vertical-align: middle;">
                                <a
                                  class="delete_btn"
                                  @click="deleteRow(index)"
                                  data-toggle="tooltip"
                                  title="Eliminar"
                                >
                                  <i class="fal fa-trash-alt fa-lg"></i>
                                </a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </tab-content>

    <div class="loader" v-if="loadingWizard"></div>
  </form-wizard>
</template>

<script>
import VueFormWizard from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";

Vue.use(VueFormWizard);

export default {
  data() {
    return {
      other_c: [
        {
          oc_description: null,
          oc_value: null,
          oc_due: 0
        }
      ],
      preContent: "",
      preStyle: {
        background: "#f2f2f2",
        fontFamily: "monospace",
        fontSize: "1em",
        display: "inline-block",
        padding: "15px 7px"
      },
      min: false,
      codigo: null,
      crearS: false,
      crearC: false,
      consolidados: [],
      consolidado_id: null,
      transportador: {},
      aerolineas: [],
      aerolineas_inventario: [],
      aerolinea_inventario_id: null,
      aerolinea_inventario: null,
      aerolinea: null,
      aerolinea_name: null,
      aeropuertos: [],
      aeropuerto_salida: null,
      aeropuerto_destino: null,
      account_information: "-PREPAID",
      agent_iata_data: null,
      fecha_vuelo: null,
      num_account: null,
      reference_num: null,
      handing_information: null,
      master_detail: null,
      optional_shipping_info: null,
      amount_insurance: null,
      total_other_charge_due_agent: null,
      total_other_charge_due_carrier: null,
      piezas: 1,
      peso: null,
      optional_shipping_info: null,
      rate_class: "",
      tarifa: null,
      descripcion: null,
      peso_cobrado: null,
      total: null,
      num_master: null,
      unidad_medida: "Kl",
      currency: "USD",
      chgs: "PP",
      tipo_transportador: "s",
      detail_data: [
        {
          id: "",
          piezas: "",
          peso_kl: "",
          unidad_medida: "",
          rate_class: "",
          peso_cobrado: "",
          tarifa: "",
          total: "",
          descripcion: "",
          commodity_item: "",
          minima: false
        }
      ],
      shipper: {
        id: null,
        name: null,
        direccion: null,
        ciudad: null,
        contacto: null,
        crear: false
      },
      datos_shipper: null,
      consignee: {
        id: null,
        name: null,
        direccion: null,
        ciudad: null,
        contacto: null,
        crear: false
      },
      datos_consignee: null,
      carrier: {
        id: null,
        name: null,
        direccion: null,
        ciudad: null,
        contacto: null,
        crear: false
      },
      datos_carrier: null,
      loadingWizard: false,
      disableAerolinea: false,
      errorMsg: null,
      msg: null,
      count: 0,
      editing: false,
      write: false,
      icon_cost: "fal fa-user-edit",
      icon_title: "Escribir"
    };
  },
  props: ["master", "consol", "peso_consolidado", "piezas_consolidado"],
  watch: {
    // peso: function() {
    //   // console.log('cobrado: ', this.peso_cobrado);
    //   // if(this.peso_cobrado === '' || this.peso_cobrado === null || this.peso_cobrado == 0){
    //   // this.peso_cobrado = this.peso;
    //   // }
    // },
    // piezas: function() {
    //   // this.peso_cobrado = this.peso;
    // },
    // peso_cobrado: function() {
    //   if (this.tarifa !== "" && this.tarifa !== null && this.tarifa != 0) {
    //     this.total = isInteger(this.peso_cobrado * this.tarifa);
    //   }
    // },
    // tarifa: function() {
    //   if (this.tarifa !== "" && this.tarifa !== null && this.tarifa != 0) {
    //     this.total = isInteger(this.peso_cobrado * this.tarifa);
    //   }
    //   if (this.peso_consolidado != 0 && this.peso_consolidado != "") {
    //     // this.peso = this.peso_consolidado;
    //   }
    // },
    write: function(value) {
      if (value) {
        this.icon_cost = "fal fa-hand-pointer";
        this.icon_title = "Seleccionar";
        this.text_cost = "Descripción";
      } else {
        this.icon_cost = "fal fa-user-edit";
        this.icon_title = "Escribir";
        this.text_cost = "Seleccionar Costo o Gasto";
      }
    }
  },
  created() {
    if (this.master != null) {
      this.editing = true;
      this.edit(this.master);
    }
    if (this.consol != null) {
      this.consolidado_id = {
        id: this.consol
      };
    }
    if (this.peso_consolidado != 0 && this.peso_consolidado != "") {
      console.log(this.peso_consolidado);
    }
    if (this.piezas_consolidado != null) {
      this.piezas = this.piezas_consolidado;
    }
    this.getAerolineas("aerolineas");
    this.getAerolineas("aeropuertos");
    this.getOtherCharges();
    let me = this;
    bus.$on("assignTransport", function(payload) {
      // console.log("id: ", payload.id, " nombre: ", payload.nombre);
      // me.setData(payload);
      // if (payload.type == "s") {
      //   me.shipper.id = payload.id;
      //   me.shipper.name = payload.nombre;
      //   me.datos_shipper =
      //     "\nEmail: " + payload.email + "\n" + payload.information;
      // } else if (payload.type == "c") {
      //   me.consignee.id = payload.id;
      //   me.consignee.name = payload.nombre;
      //   me.datos_consignee =
      //     "\nEmail: " + payload.email + "\n" + payload.information;
      // } else {
      //   me.carrier.id = payload.id;
      //   me.carrier.name = payload.nombre;
      //   me.datos_carrier =
      //     "\nEmail: " + payload.email + "\n" + payload.information;
      // }
    });
  },
  methods: {
    calculateTotal(index) {
      console.log(this.detail_data[index]);
      if (
        this.detail_data[index].tarifa !== "" &&
        this.detail_data[index].tarifa !== null &&
        this.detail_data[index].tarifa != 0
      ) {
        this.detail_data[index].total = isInteger(
          this.detail_data[index].peso_cobrado * this.detail_data[index].tarifa
        );
      }
    },
    open(type, edit) {
      // this.shipper.name = "";
      // this.shipper.id = "";
      let id = this.shipper.id;
      let tableD = "shipper";
      if (type == "c") {
        id = this.consignee.id;
        tableD = "consignee";
      }
      if (type == "cr") {
        id = this.carrier.id;
        tableD = "carrier";
      }
      var data = {
        component: "form-transporter",
        title: "Transportador",
        icon: "fal fa-user",
        field_id: edit ? id : null,
        table: "shipper",
        hidden_btn: true,
        edit: edit,
        type: type
      };
      bus.$emit("open", data);
    },
    addRowDetail: function() {
      this.detail_data.push({
        id: "",
        piezas: "",
        peso_kl: "",
        unidad_medida: "",
        rate_class: "",
        peso_cobrado: "",
        tarifa: "",
        total: "",
        descripcion: "",
        commodity_item: "",
        minima: false
      });
    },
    deleteRowDetail(index) {
      this.detail_data.splice(index, 1);
    },
    addOtherChargue: function() {
      this.other_c.push({
        oc_description: null,
        oc_value: null,
        oc_due: 0
      });
    },
    deleteRow(index) {
      this.other_c.splice(index, 1);
      this.setDueAgent();
    },
    setDueAgent() {
      var objeto = this.other_c;
      var total_c = 0;
      var total_a = 0;
      for (var i in objeto) {
        if (objeto[i].oc_due == "1") {
          total_c += parseFloat(
            objeto[i].oc_value == "" ? 0 : objeto[i].oc_value
          );
        } else {
          if (objeto[i].oc_due == "0") {
            total_a += parseFloat(
              objeto[i].oc_value == "" ? 0 : objeto[i].oc_value
            );
          }
        }
      }
      this.total_other_charge_due_carrier = total_c;
      this.total_other_charge_due_agent = total_a;
    },
    getOtherCharges: function() {
      let url = null;
      if (this.editing) {
        url = "../getOtherCharges/" + this.master;
        axios.get(url).then(response => {
          var obj = response.data.data;
          if (Object.keys(obj).length !== 0) {
            this.other_c = obj;
          }
        });
      }
    },
    onComplete: function() {
      if (!this.editing) {
        this.store();
      } else {
        this.update();
      }
    },
    setLoading: function(value) {
      this.loadingWizard = value;
    },
    handleValidation: function(isValid, tabIndex) {
      // console.log('Tab: '+tabIndex+ ' valid: '+isValid)
    },
    handleErrorMessage: function(errorMsg) {
      this.errorMsg = errorMsg;
    },
    setData: function(data, tipo) {
      if (data.type) {
        if (data.type == "c") {
          this.consignee = data;
          this.datos_consignee = this.setDataSending(data);
        } else if (data.type == "cr") {
          this.carrier = data;
          this.datos_carrier = this.setDataSending(data);
        } else {
          this.shipper = data;
          this.datos_shipper = this.setDataSending(data);
        }
      }
    },
    setDataSending(data) {
      let datos = null;
      if (data.information != null) {
        datos = data.information;
      } else {
        datos =
          data.name +
          "\n" +
          "Phone: " +
          data.telefono +
          "\n" +
          (data.contacto != null ? "Contact: " + data.contacto : "") +
          "\n" +
          data.direccion +
          "\n" +
          data.ciudad +
          ", " +
          data.estado +
          " " +
          data.pais +
          " " +
          (data.zip != null ? data.zip : "");
      }
      return datos;
    },
    getOtherCharges: function() {
      let url = null;
      if (this.editing) {
        url = "../getOtherCharges/" + this.master;
        axios.get(url).then(response => {
          var obj = response.data.data;
          if (Object.keys(obj).length !== 0) {
            this.other_c = obj;
          }
        });
      }
    },
    onComplete: function() {
      if (!this.editing) {
        this.store();
      } else {
        this.update();
      }
    },
    setLoading: function(value) {
      this.loadingWizard = value;
    },
    handleValidation: function(isValid, tabIndex) {
      // console.log('Tab: '+tabIndex+ ' valid: '+isValid)
    },
    handleErrorMessage: function(errorMsg) {
      this.errorMsg = errorMsg;
    },
    validar_primer_tab: function() {
      return new Promise((resolve, reject) => {
        this.$validator
          .validateAll(["nombre", "nombreC", "nombreCR"])
          .then(isValid => {
            resolve(isValid);
          });
      });
    },
    validar_segundo_tab: function() {
      if (this.num_master == null) {
        this.msg =
          "Debe seleccionar una aerolinea y un inventario de aerolinea para que el número de master se genere";
        return false;
      }
      return new Promise((resolve, reject) => {
        this.$validator
          .validateAll([
            "aeropuerto_salida",
            "aeropuerto_destino",
            "account_information",
            "fecha_vuelo",
            "currency",
            "chgss"
          ])
          .then(isValid => {
            resolve(isValid);
          });
      });
    },
    validar_tercer_tab: function() {
      return new Promise((resolve, reject) => {
        this.$validator
          .validateAll([
            "piezas",
            "peso",
            "unidad_medida",
            "peso_cobrado",
            "tarifa",
            "total",
            "descripcion"
          ])
          .then(isValid => {
            resolve(isValid);
          });
      });
    },
    store: function() {
      axios
        .post("/master", {
          num_master: this.num_master,
          shipper_id: this.shipper.id,
          shipper: this.datos_shipper,
          consignee_id: this.consignee.id,
          consignee: this.datos_consignee,
          carrier_id: this.carrier.id,
          carrier: this.datos_carrier,
          aerolinea_inventario_id: !this.write
            ? this.aerolinea_inventario_id.id
            : "",
          aerolineas_id: this.aerolinea.id,
          by_first_carrier: this.aerolinea_name,
          aeropuertos_id: this.aeropuerto_salida.id,
          aeropuertos_id_destino: this.aeropuerto_destino.id,
          account_information: this.account_information,
          agent_iata_data: this.agent_iata_data,
          num_account: this.num_account,
          reference_num: this.reference_num,
          optional_shipping_info: this.optional_shipping_info,
          amount_insurance: this.amount_insurance,
          total_other_charge_due_agent: this.total_other_charge_due_agent,
          total_other_charge_due_carrier: this.total_other_charge_due_carrier,
          currency: this.currency,
          chgs_code: this.chgs,
          fecha_vuelo1: this.fecha_vuelo,
          fecha_vuelo2: this.fecha_vuelo,
          // DETALLE
          detail: this.detail_data,
          // piezas: this.piezas,
          // peso: this.peso,
          // unidad_medida: this.unidad_medida,
          // rate_class: this.rate_class,
          // commodity_item: this.commodity_item,
          // peso_cobrado: this.peso_cobrado,
          // tarifa: this.tarifa ? this.tarifa : 0,
          // minima: this.min,
          // total: this.total,
          // descripcion: this.descripcion,
          handing_information: this.handing_information,
          master_detail: this.master_detail,
          consolidado_id:
            this.consolidado_id != null ? this.consolidado_id.id : null,
          to1: this.aeropuerto_destino.codigo,
          other_c: this.other_c,
          created_at: this.getTime()
        })
        .then(response => {
          toastr.success("Registro exitoso.");
          window.open(
            "../../imprimir/" + response.data.id_master + "/" + true,
            "_blank"
          );
          location.href = "/master";
        });
    },
    update: function() {
      axios
        .put("/master/" + this.master, {
          aerolinea_inventario_id: !this.write
            ? this.aerolinea_inventario_id.id
            : "",
          aerolineas_id: this.aerolinea.id,
          shipper_id: this.shipper.id,
          shipper: this.datos_shipper,
          consignee_id: this.consignee.id,
          consignee: this.datos_consignee,
          carrier_id: this.carrier.id,
          carrier: this.datos_carrier,
          aeropuertos_id: this.aeropuerto_salida.id,
          aeropuertos_id_destino: this.aeropuerto_destino.id,
          account_information: this.account_information,
          agent_iata_data: this.agent_iata_data,
          num_account: this.num_account,
          num_master: this.num_master,
          reference_num: this.reference_num,
          optional_shipping_info: this.optional_shipping_info,
          amount_insurance: this.amount_insurance,
          total_other_charge_due_agent: this.total_other_charge_due_agent,
          total_other_charge_due_carrier: this.total_other_charge_due_carrier,
          currency: this.currency,
          chgs_code: this.chgs,
          fecha_vuelo1: this.fecha_vuelo,
          fecha_vuelo2: this.fecha_vuelo,
          // DETALLE
          detail: this.detail_data,
          // piezas: this.piezas,
          // peso: this.peso,
          // unidad_medida: this.unidad_medida,
          // rate_class: this.rate_class,
          // commodity_item: this.commodity_item,
          // peso_cobrado: this.peso_cobrado,
          // tarifa: this.tarifa,
          // minima: this.min,
          // total: this.total,
          // descripcion: this.descripcion,

          handing_information: this.handing_information,
          master_detail: this.master_detail,
          consolidado_id:
            this.consolidado_id != null ? this.consolidado_id.id : null,
          to1: this.aeropuerto_destino.codigo,
          other_c: this.other_c,
          updated_at: this.getTime()
        })
        .then(response => {
          toastr.success("Actualización exitosa.");
          window.open(
            "../imprimir/" + response.data.id_master + "/" + true,
            "_blank"
          );
          location.href = "/master";
        });
    },
    edit(id) {
      let me = this;
      axios.get("../" + id).then(({ data }) => {
        // this.disableAerolinea = true;
        this.editing = true;
        this.shipper.disabled = false;

        this.shipper.id = data.data.shipper_id;
        this.shipper.name = data.data.nombre_shipper;

        this.shipper.direccion = data.data.direccion_shipper;
        this.shipper.telefono = data.data.telefono_shipper;
        this.shipper.zip = data.data.zip_shipper;
        this.shipper.ciudad = data.data.ciudad_shipper;
        this.shipper.contacto = data.data.contacto_shipper;
        this.shipper.estado = data.data.estado_shipper;
        this.shipper.pais = data.data.pais_shipper;

        this.consignee.disabled = false;
        this.consignee.id = data.data.consignee_id;
        this.consignee.name = data.data.nombre_consignee;
        this.consignee.nombre = data.data.nombre_consignee;
        this.consignee.direccion = data.data.direccion_consignee;
        this.consignee.telefono = data.data.telefono_consignee;
        this.consignee.zip = data.data.zip_consignee;
        this.consignee.ciudad = data.data.ciudad_consignee;
        this.consignee.contacto = data.data.contacto_consignee;
        this.consignee.estado = data.data.estado_consignee;
        this.consignee.pais = data.data.pais_consignee;

        this.carrier.id = data.data.carrier_id;
        this.carrier.name = data.data.nombre_carrier;
        this.carrier.nombre = data.data.nombre_carrier;
        this.carrier.direccion = data.data.direccion_carrier;
        this.carrier.telefono = data.data.telefono_carrier;
        this.carrier.zip = data.data.zip_carrier;
        this.carrier.ciudad = data.data.ciudad_carrier;
        this.carrier.contacto = data.data.contacto_carrier;
        this.carrier.estado = data.data.estado_carrier;
        this.carrier.pais = data.data.pais_carrier;

        setTimeout(function() {
          me.datos_shipper = data.data.shipper;
          me.datos_consignee = data.data.consignee;
          me.datos_carrier = data.data.carrier;
          // aerolineas
          var aer = me.aerolineas.filter(
            ({ id }) => id == data.data.aerolineas_id
          );
          me.aerolinea = aer[0];
          me.aerolinea_inventario_id =
            data.data.aerolinea_inventario !== null
              ? data.data.codigo_aerolinea +
                " " +
                data.data.aerolinea_inventario
              : "";

          // aeropuertos
          var departure = me.aeropuertos.filter(
            ({ id }) => id == data.data.aeropuertos_id
          );
          me.aeropuerto_salida = departure[0];
          var arrival = me.aeropuertos.filter(
            ({ id }) => id == data.data.aeropuertos_id_destino
          );
          me.aeropuerto_destino = arrival[0];
        }, 1500);
        // console.log(data.data.aerolinea_inventario);
        if (data.data.aerolinea_inventario === null) {
          me.write = true;
        }
        this.num_master = data.data.num_master;
        this.consolidado_id = data.data.consolidado_id;
        this.account_information = data.data.account_information;
        this.handing_information = data.data.handing_information;
        this.master_detail = data.data.master_detail;
        this.agent_iata_data = data.data.agent_iata_data;
        this.num_account = data.data.num_account;
        this.reference_num = data.data.reference_num;
        this.optional_shipping_info = data.data.optional_shipping_info;
        this.amount_insurance = data.data.amount_insurance;
        this.currency = data.data.currency;
        this.chgs = data.data.chgs_code;
        this.fecha_vuelo = data.data.fecha_vuelo1;
        this.fecha_vuelo = data.data.fecha_vuelo2;
        // DETALLE
        this.detail_data = data.detalle;
        // this.piezas = data.detalle.piezas;
        // this.peso = data.detalle.peso_kl;
        // this.unidad_medida = data.detalle.unidad_medida;
        // this.rate_class = data.detalle.rate_class;
        // this.commodity_item = data.detalle.commodity_item;
        // this.peso_cobrado = data.detalle.peso_cobrado;
        // this.tarifa = data.detalle.tarifa;
        // this.min = data.detalle.minima;
        // this.total = data.detalle.total;
        // this.descripcion = data.detalle.descripcion;

        this.total_other_charge_due_agent =
          data.data.total_other_charge_due_agent;
        this.total_other_charge_due_carrier =
          data.data.total_other_charge_due_carrier;
        if (data.data.consolidado_id != null) {
          this.consolidado_id = {
            id: data.data.consolidado_id,
            consolidado: data.data.consolidado,
            fecha: data.data.fecha,
            pais: data.data.pais
          };
        }
      });
    },
    getAerolineas: function(tipo) {
      let url = null;
      if (!this.editing) {
        url = "/transport/" + tipo + "/all";
      } else {
        url = "../../transport/" + tipo + "/all";
      }
      axios.get(url).then(response => {
        if (tipo == "aerolineas") {
          this.aerolineas = response.data.data;
        } else {
          this.aeropuertos = response.data.data;
        }
      });
    },
    getAerolineasInventario: function(val) {
      let me = this;
      // console.log(
      //   "ENsadklamsdlkamlkm",
      //   me.aerolinea_inventario_id,
      //   " - ",
      //   me.num_master
      // );

      me.aerolinea_inventario_id = null;
      me.num_master = "";
      if (val != null) {
        this.codigo = val.codigo;
        this.aerolinea = val;
        this.aerolinea_name = val.nombre;
        let url = "/aerolinea_inventario/get/" + val.id;
        if (this.editing) {
          url = "../../aerolinea_inventario/get/" + val.id;
        }
        axios.get(url).then(response => {
          this.aerolineas_inventario = response.data;
        });
      } else {
        this.codigo = null;
        this.aerolinea = null;
        this.aerolineas_inventario = [];
      }
    },
    setNumMaster: function(val) {
      if (val != null) {
        this.msg = null;
        this.aerolinea_inventario_id = val;
        this.num_master = val.nombre;
      } else {
        if (!this.write) {
          this.num_master = null;
        }
      }
    },
    onSearchConsolidados(search, loading) {
      loading(true);
      this.searchConsolidados(loading, search, this);
    },
    searchConsolidados: _.debounce((loading, search, vm) => {
      if (vm.editing) {
        fetch(`../../vueSelectConsolidados/${escape(search)}`).then(res => {
          res.json().then(json => (vm.consolidados = json.items));
          loading(false);
        });
      } else {
        fetch(`../../vueSelectConsolidados/${escape(search)}`).then(res => {
          res.json().then(json => (vm.consolidados = json.items));
          loading(false);
        });
      }
    }, 350)
  }
};
</script>
