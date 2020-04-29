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
    function docRenderer(instance, td, row, col, prop, value, cellProperties) {
      td.className = "htMiddle htCenter is-readOnly";
      let dataRow = instance.getDataAtRow(row);
      let btn_track =
        '<a class="btn btn-info btn-xs btn-actions addTrackings" type="button" id="btn_addtracking' +
        dataRow[9] +
        '" data-toggle="tooltip"' +
        ' onclick="addTrackings(' +
        dataRow[9] +
        ')" data-original-title="Agregar tracking"><i class="fal fa-truck"></i> <span id="cant_tracking' +
        dataRow[9] +
        '">' +
        dataRow[12] +
        "</span></a>";
      td.innerHTML = btn_track + " " + value;
    }
    function btnRenderer(instance, td, row, col, prop, value, cellProperties) {
      td.className = "htMiddle htCenter";
      let dataRow = instance.getDataAtRow(row);
      let btn_delete =
        '<button onclick="eliminar(' +
        dataRow[9] +
        ', false)" class="btn btn-danger btn-circle" type="button" data-toggle="tooltip" data-original-title="Eliminar"><i class="fal fa-trash-alt"></i></button>';

      td.innerHTML = btn_delete;
    }
    function paRenderer(instance, td, row, col, prop, value, cellProperties) {
      let dataRow = instance.getDataAtRow(row);
      td.className = "htMiddle htCenter is-readOnly";
      td.innerHTML =
        value +
        ' <a data-toggle="tooltip" title="" class="edit" style="float: right; color: rgb(255, 193, 7); " onclick="showModalArancel(' +
        dataRow[9] +
        ', \'whgTable\')" data-original-title="Cambiar"><i class="fal fa-pencil"></i></a>';
    }
    function contentRenderer(
      instance,
      td,
      row,
      col,
      prop,
      value,
      cellProperties
    ) {
      // td.className = "htMiddle htCenter is-readOnly";
      td.innerHTML = value;
    }
    return {
      data: [],
      settings: {
        licenseKey: "non-commercial-and-evaluation",
        nestedHeaders: [
          [
            { rowspan: 2, colspan: 3 },
            { label: "Dimensiones", colspan: 3 },
            { rowspan: 2, colspan: 4 }
          ],
          [
            "Código",
            "Pieza(s)",
            "Peso(Lb)",
            "Largo",
            "Ancho",
            "Alto",
            "Contenido",
            "Cód. Aduana",
            "Valor US$",
            "Acción"
          ]
        ],
        stretchH: "all",
        className: "htMiddle htCenter",
        columns: [
          {
            data: "num_warehouse",
            readOnly: true,
            readOnlyCellClassName: "is-readOnly",
            renderer: docRenderer
          },
          {
            data: "piezas",
            type: "numeric",
            numericFormat: {
              pattern: "0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "peso",
            type: "numeric",
            numericFormat: {
              pattern: "0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "largo",
            type: "numeric",
            numericFormat: {
              pattern: "0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "ancho",
            type: "numeric",
            numericFormat: {
              pattern: "0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          {
            data: "alto",
            type: "numeric",
            numericFormat: {
              pattern: "0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
          { data: "contenido", width: "250", renderer: contentRenderer },
          {
            data: "nom_pa",
            width: "120",
            renderer: paRenderer,
            readOnly: true
          },
          {
            data: "valor",
            type: "numeric",
            numericFormat: {
              pattern: "$ 0,0",
              culture: "en-US" // this is the default culture, set up for USD
            }
          },
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
          columns: [10, 11, 12]
        },
        rowHeights: 40,
        // width: "100%",
        fixedColumnsLeft: 1,
        height: function() {
          // numero_filas * 40 + 40;
          let hei = 800;
          if ($(".ht_master .wtHider").height() <= 800) {
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
          let piezas = 0;
          let volumen = 0;
          let peso = 0;
          let valor = 0;
          if (this.data) {
            this.data.forEach(el => {
              piezas += parseFloat(el.piezas);
              volumen += parseFloat(el.volumen);
              peso += parseFloat(el.peso);
              valor += parseFloat(el.valor);
            });
            /*Update footer formatCurrency()*/
            $("#piezas").html(parseFloat(isInteger(piezas)));
            $("#volumen").html(parseFloat(isInteger(Math.ceil(volumen))));
            $("#pie_ft").html(
              parseFloat(isInteger(Math.ceil((volumen * 166) / 1728)))
            );
            $("#pesoDim").html(parseFloat(isInteger(peso)));
            $("#valor_declarado_tbl").html(parseFloat(isInteger(valor)));

            $("#piezas1").val(parseFloat(isInteger(piezas)));
            $("#volumen1").val(parseFloat(isInteger(Math.ceil(volumen))));
            $("#pie_ft1").val(
              parseFloat(isInteger(Math.ceil((volumen * 166) / 1728)))
            );
            $("#pesoDim1").val(parseFloat(isInteger(peso)));
            $("#valor_declarado_tbl1").val(parseFloat(isInteger(valor)));
            setTimeout(function() {
              totalizeDocument();
            }, 1000);
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    updateDataDetailNew(data) {
      let me = this;
      axios
        .post("updateDetailDocument", data)
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