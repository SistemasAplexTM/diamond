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

export default {
  data: function() {
    function btnRenderer(instance, td, row, col, prop, value, cellProperties) {
      // const button = document.createElement("button");
      // button.type = "button";
      td.className = "htMiddle htCenter";
      td.innerHTML =
        '<button type="button" class="btn btn-success dropdown-toggle btn-circle-sm htMiddle" data-toggle="dropdown" ><i class="fal fa-ellipsis-v"></i></button>';
      // td.appendChild(button);
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
          "Acción"
        ],
        rowHeaders: true,
        className: "htMiddle htCenter",
        columns: [
          { data: "num_bolsa" },
          { data: "num_warehouse", readOnly: true },
          { data: "shipper_data", width: "250", className: "htLeft" },
          { data: "consignee_data", width: "250", className: "htLeft" },
          { data: "pa", width: "150", readOnly: true },
          { data: "contenido2", width: "400", className: "htLeft" },
          { data: "declarado2" },
          { data: "peso2_sum" },
          { data: "peso" },
          {
            data: "id",
            renderer: btnRenderer,
            readOnly: true
          }
        ],
        rowHeights: 100,
        width: "100%",
        fixedColumnsLeft: 2,
        height: 600,
        afterChange: changes => {
          if (changes) {
            console.log(this.data[changes[0][0]]);
          }

          console.log(changes);
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
    getData() {
      axios
        .get("getAllConsolidadoDetalle")
        .then(({ data }) => {
          this.data = data.data;
          console.log(data);
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style src="../../../../node_modules/handsontable/dist/handsontable.full.css"></style>