<template>
  <div>
    <hot-table
      :data="data"
      :settings="settings"
      :colHeaders="settings.colHeaders"
      class="hot handsontable htRowHeaders htColumnHeaders"
    ></hot-table>
    <div style="text-align: end;font-size: 20px;">
      <span style>Piezas: {{ formatNumber(piezas) }}</span>&nbsp;&nbsp;&nbsp;
      <span style="color:tomato">Declarado: $ {{ formatNumber(declarado) }}</span>&nbsp;&nbsp;&nbsp;
      <span style="color:blue">Peso: {{ formatNumber(peso) }}</span>&nbsp;&nbsp;&nbsp;
      <span style>Peso Real: {{ formatNumber(peso_real) }}</span>
    </div>
  </div>
</template>

<script>
import { HotTable } from "@handsontable/vue";
import Handsontable from "handsontable";

export default {
  props: {
    update_detail: {
      type: Boolean
    },
    type_consol: [String, Number]
  },
  watch: {
    update_detail: function(val) {
      this.getData();
    }
  },
  data: function() {
    function btnRenderer(instance, td, row, col, prop, value, cellProperties) {
      td.className = "htMiddle htCenter";
      let dataRow = instance.getDataAtRow(row);
      let btn_delete =
        '<button onclick="eliminarConsolidado(' +
        dataRow[10] +
        ', false)" class="btn btn-danger btn-circle" type="button" data-toggle="tooltip" data-original-title="Eliminar"><i class="fal fa-trash-alt"></i></button>';
      // BTN LABEL - PRINTS
      let url_print_label =
        "../../impresion-documento-label/" +
        dataRow[12] +
        "/guia/" +
        dataRow[11] +
        "/consolidado/" +
        dataRow[10];
      let btn_print_label =
        "<a onclick=\"printDocuments('" +
        url_print_label +
        "', 'label')\"" +
        ' class="btn btn-circle btn-default btn-outline" type="button" data-toggle="tooltip" data-original-title="Label" target="blank_"><i class="fal fa-barcode"></i></a>';
      // BTN DOCUMENT - PRINTS
      let url_print_invoice =
        "../../impresion-documento/" + dataRow[12] + "/invoice/" + dataRow[11];
      let btn_print_invoice =
        "<a onclick=\"printDocuments('" +
        url_print_invoice +
        "', 'document')\"" +
        ' class="btn btn-circle btn-default btn-outline" type="button" data-toggle="tooltip" data-original-title="Factura" target="blank_"><i class="fal fa-file"></i></a>';
      td.innerHTML =
        btn_print_label + " " + btn_print_invoice + " " + btn_delete;
    }
    function paRenderer(instance, td, row, col, prop, value, cellProperties) {
      let dataRow = instance.getDataAtRow(row);
      td.className = "htMiddle htCenter is-readOnly";
      td.innerHTML =
        value +
        ' <a data-toggle="tooltip" title="" class="edit" style="float: right; color: rgb(255, 193, 7); " onclick="showModalArancel(' +
        dataRow[11] +
        ', \'tbl-consolidado\')" data-original-title="Cambiar"><i class="fal fa-pencil"></i></a>';
      // if (cellProperties.row == 0) {
      //   cellProperties.instance.setDataAtCell(0, 4, "nuevo valor");
      //   console.log(cellProperties);
      // }
    }
    function shipperRenderer(
      instance,
      td,
      row,
      col,
      prop,
      value,
      cellProperties
    ) {
      let dataRow = instance.getDataAtRow(row);
      td.innerHTML =
        value +
        '<div style="width:20%;"><a  data-toggle="tooltip" title="Cambiar" class="edit" style="color:#FFC107;" onclick="showModalShipperConsigneeConsolidado(' +
        dataRow[10] +
        ", '" +
        dataRow[13] +
        '\', \'shipper\')"><i class="fal fa-pencil"></i></a> <a onclick="restoreShipperConsignee(' +
        dataRow[10] +
        ', \'shipper\')" class="delete" title="Restaurar original" data-toggle="tooltip" style="float:right;color:#2196F3;margin-right: 5px;">' +
        '<i class="fal fa-sync-alt"></i></a></div>';
    }
    function consigneeRenderer(
      instance,
      td,
      row,
      col,
      prop,
      value,
      cellProperties
    ) {
      let dataRow = instance.getDataAtRow(row);
      td.innerHTML =
        value +
        '<div style="width:20%;"><a  data-toggle="tooltip" title="Cambiar" class="edit" style="color:#FFC107;" onclick="showModalShipperConsigneeConsolidado(' +
        dataRow[10] +
        ", '" +
        dataRow[14] +
        '\', \'consignee\')"><i class="fal fa-pencil"></i></a> <a onclick="restoreShipperConsignee(' +
        dataRow[10] +
        ', \'consignee\')" class="delete" title="Restaurar original" data-toggle="tooltip" style="float:right;color:#2196F3;margin-right: 5px;">' +
        '<i class="fal fa-sync-alt"></i></a></div>';
    }
    return {
      data: [],
      declarado: 0,
      peso: 0,
      peso_real: 0,
      piezas: 0,
      settings: {
        licenseKey: "non-commercial-and-evaluation",
        colHeaders: [
          "Tula",
          "Guía/WRH",
          "Remitente",
          "Destinatario",
          "P.A",
          "Descripción",
          "Piezas",
          "Dec.",
          "Lb",
          "Lb R",
          "Acción",
          "documento_detalle_id",
          "documento_id"
        ],
        rowHeaders: true,
        stretchH: "all",
        className: "htMiddle htCenter",
        columns: [
          { data: "num_bolsa" },
          {
            data: "num_warehouse",
            readOnly: true,
            readOnlyCellClassName: "is-readOnly"
          },
          {
            data: "shipper_data",
            width: "250",
            className: "htLeft",
            renderer: shipperRenderer
          },
          {
            data: "consignee_data",
            width: "250",
            className: "htLeft",
            renderer: consigneeRenderer
          },
          {
            data: "pa",
            width: "120",
            renderer: paRenderer,
            readOnly: true
          },
          { data: "contenido2", width: "400", className: "htLeft" },
          {
            data: "piezas",
            type: "numeric",
            numericFormat: {
              pattern: "0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "declarado2",
            type: "numeric",
            numericFormat: {
              pattern: "$ 0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "peso2_sum",
            type: "numeric",
            numericFormat: {
              pattern: "0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "peso",
            readOnly: true,
            readOnlyCellClassName: "is-readOnly",
            type: "numeric",
            numericFormat: {
              pattern: "0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "id",
            renderer: btnRenderer,
            readOnly: true
          },
          {
            data: "documento_detalle_id",
            readOnly: true
          },
          {
            data: "documento_id",
            readOnly: true
          },
          {
            data: "shipper_id",
            readOnly: true
          },
          {
            data: "consignee_id",
            readOnly: true
          }
        ],
        hiddenColumns: {
          columns: [11, 12, 13, 14]
        },
        rowHeights: 100,
        width: "100%",
        fixedColumnsLeft: 2,
        height: function() {
          let hei = 5050;
          if ($(".ht_master .wtHider").height() <= 5050) {
            hei = parseInt($(".ht_master .wtHider").height()) + 20;
          }
          return hei;
        },
        afterChange: changes => {
          if (changes) {
            for (let i = 0; i < changes.length; i++) {
              var row = this.data[changes[i][0]];
              var data = {
                id: row["id"],
                option: changes[i][1],
                id_detail: row["documento_detalle_id"],
                data: changes[i][3]
              };
              this.updateDataDetailNew(data);
            }
            toastr.success("Actualizado con éxito");
          }
        }
      }
    };
  },
  components: {
    HotTable
  },
  created() {
    let me = this;
    me.getData();
    bus.$on("updatetableconsolidated", function(payload) {
      me.getData();
    });
  },
  methods: {
    deleteRow(data) {
      console.log("delete_r: " + data);
    },
    getData() {
      this.declarado = 0;
      this.peso = 0;
      this.peso_real = 0;
      this.piezas = 0;
      axios
        .get("getAllConsolidadoDetalle")
        .then(({ data }) => {
          this.data = data.data;
          if (this.data) {
            this.data.forEach(el => {
              this.declarado += parseFloat(el.declarado2);
              this.peso += parseFloat(el.peso2);
              this.piezas += parseFloat(el.piezas);
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    updateDataDetailNew(data) {
      let me = this;
      axios
        .post("updateDetailConsolidado", data)
        .then(response => {
          if (response.data.code == 200) {
            this.getData();
          } else {
            console.log("error");
          }
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    }
  }
};
</script>

<style src="../../../../node_modules/handsontable/dist/handsontable.full.css"></style>