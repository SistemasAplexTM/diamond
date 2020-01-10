<template>
  <div>
    <h1>Hola mundo!</h1>
    <JqxGrid
      @cellbeginedit="onCellBeginEdit($event)"
      @cellendedit="onCellEndEdit($event)"
      :width="'50%'"
      :source="dataAdapter"
      :columns="columns"
      :editable="true"
    />
  </div>
</template>
<script>
import JqxGrid from "jqwidgets-scripts/jqwidgets-vue/vue_jqxgrid.vue";
export default {
  components: {
    JqxGrid
  },
  data: function() {
    return {
      dataAdapter: new jqx.dataAdapter(this.source),
      columns: [
        { text: "Name", datafield: "descripcion", width: 240 },
        { text: "Iso", datafield: "iso2", width: 240 },
        { text: "Created", datafield: "created_at", width: 150 }
      ]
    };
  },
  beforeCreate: function() {
    this.source = {
      datatype: "json",
      datafields: [
        { name: "id" },
        { name: "descripcion" },
        { name: "iso2" },
        { name: "created_at" }
      ],
      url: "pais/all"
    };
  },
  methods: {
    onCellBeginEdit: function(event) {
      let args = event.args;
      let columnDataField = args.datafield;
      let rowIndex = args.rowindex;
      let cellValue = args.value;
    },
    onCellEndEdit: function(event) {
      console.log("onCellEndEdit: ", event);
      let args = event.args;
      let columnDataField = args.datafield;
      let rowIndex = args.rowindex;
      let cellValue = args.value;
      let oldValue = args.oldvalue;
      alert("Enviando llamado ajax: " + cellValue + " id: " + args.row.id);
    }
  }
};
</script>