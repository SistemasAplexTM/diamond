<template>
  <hot-table
    :data="data"
    :settings="settings"
    :colHeaders="settings.colHeaders"
    class="hot handsontable htRowHeaders htColumnHeaders"
  ></hot-table>
</template>

<script>
import { HotTable } from "@handsontable/vue";
import Handsontable from "handsontable";

export default {
  props: {
    update_detail: {
      type: Boolean
    }
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
        '<a onclick="eliminarConsolidado(' +
        dataRow[9] +
        ', false)" class="delete_btn" data-toggle="tooltip" data-original-title="Eliminar"><i class="fal fa-trash-alt fa-lg"></i></a>';
      let btn_print_label =
        '<a href="../../impresion-documento-label/' +
        dataRow[11] +
        "/guia/" +
        dataRow[10] +
        "/consolidado/" +
        dataRow[9] +
        '" class="print_btn" target="blank_" data-toggle="tooltip" data-original-title="Label"><i class="fal fa-barcode fa-lg"></i></a>';
      let btn_print_invoice =
        '<a href="../../impresion-documento/' +
        dataRow[11] +
        "/invoice/" +
        dataRow[10] +
        '" class="print_btn" target="blank_" data-toggle="tooltip" data-original-title="Factura"><i class="fal fa-file fa-lg"></i></a>';
      td.innerHTML =
        btn_print_label + " " + btn_print_invoice + " " + btn_delete;
    }
    function paRenderer(instance, td, row, col, prop, value, cellProperties) {
      let dataRow = instance.getDataAtRow(row);
      td.className = "htMiddle htCenter is-readOnly";
      td.innerHTML =
        value +
        ' <a data-toggle="tooltip" title="" class="edit" style="float: right; color: rgb(255, 193, 7); " onclick="showModalArancel(' +
        dataRow[10] +
        ', \'tbl-consolidado\')" data-original-title="Cambiar"><i class="fal fa-pencil"></i></a>';
      // if (cellProperties.row == 0) {
      //   cellProperties.instance.setDataAtCell(0, 4, "nuevo valor");
      //   console.log(cellProperties);
      // }
    }
    return {
      data: [],
      settings: {
        licenseKey: "non-commercial-and-evaluation",
        colHeaders: [
          "Tula",
          "Guía/WRH",
          "Remitente",
          "Destinatario",
          "P.A",
          "Descripción",
          "Dec.",
          "Lb",
          "Lb R",
          "Acción",
          "documento_detalle_id",
          "documento_id"
        ],
        rowHeaders: true,
        className: "htMiddle htCenter",
        columns: [
          { data: "num_bolsa" },
          {
            data: "num_warehouse",
            readOnly: true,
            readOnlyCellClassName: "is-readOnly"
          },
          { data: "shipper_data", width: "250", className: "htLeft" },
          { data: "consignee_data", width: "250", className: "htLeft" },
          {
            data: "pa",
            width: "150",
            renderer: paRenderer,
            readOnly: true
          },
          { data: "contenido2", width: "400", className: "htLeft" },
          { data: "declarado2" },
          { data: "peso2_sum" },
          {
            data: "peso",
            readOnly: true,
            readOnlyCellClassName: "is-readOnly"
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
          }
        ],
        hiddenColumns: {
          columns: [10, 11]
        },
        rowHeights: 100,
        width: "100%",
        fixedColumnsLeft: 2,
        height: 600,
        afterChange: changes => {
          if (changes) {
            // console.log(changes);
            var row = this.data[changes[0][0]];
            // console.log(this.data[changes[0][0]]);
            var data = {
              id: row["id"],
              option: changes[0][1],
              id_detail: row["documento_detalle_id"],
              data: changes[0][3]
            };
            this.updateDataDetailNew(data);
          }
        }
      }
    };
  },
  components: {
    HotTable
  },
  created() {
    this.getData();
  },
  methods: {
    deleteRow(data) {
      console.log("delete_r: " + data);
    },
    getData() {
      axios
        .get("getAllConsolidadoDetalle")
        .then(({ data }) => {
          this.data = data.data;
          // console.log(data);
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
            toastr.success("Actualizado con éxito");
            this.getData();
            // console.log("success!", data);
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