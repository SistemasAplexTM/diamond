<template>
  <div style="width:100%">
    <hot-table
      :data="data"
      :settings="settings"
      :colHeaders="settings.colHeaders"
      class="hot handsontable htRowHeaders htColumnHeaders"
    ></hot-table>
  </div>
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
        '<button onclick="eliminar(' +
        dataRow[6] +
        ', false)" class="btn btn-danger btn-circle" type="button" data-toggle="tooltip" data-original-title="Eliminar"><i class="fal fa-trash-alt"></i></button>';
      // let btn_print_label =
      //   '<a href="../../impresion-documento-label/' +
      //   dataRow[11] +
      //   "/guia/" +
      //   dataRow[10] +
      //   "/consolidado/" +
      //   dataRow[9] +
      //   '" class="btn btn-circle btn-default btn-outline" type="button" data-toggle="tooltip" data-original-title="Label" target="blank_"><i class="fal fa-barcode"></i></a>';
      // let btn_print_invoice =
      //   '<a href="../../impresion-documento/' +
      //   dataRow[11] +
      //   "/invoice/" +
      //   dataRow[10] +
      //   '" class="btn btn-circle btn-default btn-outline" type="button" data-toggle="tooltip" data-original-title="Factura" target="blank_"><i class="fal fa-file"></i></a>';
      td.innerHTML = btn_delete;
    }
    function paRenderer(instance, td, row, col, prop, value, cellProperties) {
      let dataRow = instance.getDataAtRow(row);
      td.className = "htMiddle htCenter is-readOnly";
      td.innerHTML =
        value +
        ' <a data-toggle="tooltip" title="" class="edit" style="float: right; color: rgb(255, 193, 7); " onclick="showModalArancel(' +
        dataRow[6] +
        ', \'whgTable\')" data-original-title="Cambiar"><i class="fal fa-pencil"></i></a>';
    }
    return {
      data: [],
      settings: {
        licenseKey: "non-commercial-and-evaluation",
        colHeaders: [
          "Código",
          "Pieza(s)",
          "Peso(Lb)",
          "Contenido",
          "Cód. Aduana",
          "Valor US$",
          "Acción"
        ],
        rowHeaders: true,
        stretchH: "all",
        className: "htMiddle htCenter",
        columns: [
          {
            data: "num_warehouse",
            readOnly: true,
            readOnlyCellClassName: "is-readOnly"
          },
          {
            data: "piezas"
          },
          { data: "peso" },
          { data: "contenido" },
          {
            data: "nom_pa",
            width: "150",
            renderer: paRenderer,
            readOnly: true
          },
          { data: "valor" },
          {
            data: "id",
            width: "100",
            renderer: btnRenderer,
            readOnly: true
          },
          {
            data: "documento_id",
            readOnly: true
          },
          {
            data: "consolidado",
            readOnly: true
          },
          {
            data: "cantidad",
            readOnly: true
          }
        ],
        hiddenColumns: {
          columns: [7, 8, 9]
        },
        rowHeights: 40,
        // width: "100%",
        fixedColumnsLeft: 1,
        height: "200",
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
        .get("getDataDetailDocument")
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
      // axios
      //   .post("updateDetailConsolidado", data)
      //   .then(response => {
      //     if (response.data.code == 200) {
      //       toastr.success("Actualizado con éxito");
      //       this.getData();
      //       // console.log("success!", data);
      //     } else {
      //       console.log("error");
      //     }
      //   })
      //   .catch(function(error) {
      //     console.log(error);
      //     toastr.warning("Error: -" + error);
      //   });
    }
  }
};
</script>

<style src="../../../../node_modules/handsontable/dist/handsontable.full.css"></style>