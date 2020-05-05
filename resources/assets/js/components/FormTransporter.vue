<template lang="html">
  <div>
    <el-row :gutter="24">
      <el-col :span="8">
        <label class="control-label gcore-label-top">Nombre:</label>
      </el-col>
      <el-col :span="16">
        <el-input placeholder="Nombre" v-model="form.name" size="medium" clearable></el-input>
      </el-col>
    </el-row>
    <br>
    <el-row :gutter="24">
      <el-col :span="8">
        <label for="documento" class="control-label gcore-label-top">Correo:</label>
      </el-col>
      <el-col :span="16">
        <el-input placeholder="Correo" v-model="form.email" size="medium" clearable></el-input>
      </el-col>
    </el-row>
    <br>
    <el-row :gutter="24">
      <el-col :span="8">
        <label for="documento" class="control-label gcore-label-top">Información:</label>
      </el-col>
      <el-col :span="16">
        <el-input
          placeholder="Información..."
          v-model="form.info"
          size="medium"
           :autosize="{ minRows: 5}"
          clearable
          type="textarea"
        ></el-input>
      </el-col>
    </el-row>
    <br>
    <el-row :gutter="24">
      <el-col :span="24">
        <el-checkbox v-model="form.shipper">Shipper</el-checkbox>
        <el-checkbox v-model="form.consignee">Consignee</el-checkbox>
        <el-checkbox v-model="form.carrier">Carrier</el-checkbox>
      </el-col>
    </el-row>
  </div>
</template>
<script>
export default {
  props: ["payload"],
  data() {
    return {
      form: {
        name: "",
        email: "",
        info: null,
        shipper: false,
        consignee: false,
        carrier: false
      }
    };
  },
  created() {
    let me = this;
    bus.$on("save", function(payload) {
      me.onSubmit();
    });
    bus.$on("update", function(payload) {
      me.update();
      // me.beforeSend(true);
    });
    bus.$on("cancel", function(payload) {
      me.resetForm();
    });
    if (this.payload.field_id) {
      this.getByID(this.payload.field_id);
    }
  },
  methods: {
    onSubmit() {
      axios
        .post("/saveFromRigthMenu/transport", this.form)
        .then(({ data }) => {
          data.type = this.payload.type;
          bus.$emit("assignTransport", data);
          bus.$emit("close");
          this.resetForm();
        })
        .catch(error => {
          console.log(error);
        });
    },
    resetForm() {
      this.form = {
        name: null,
        email: null,
        info: null
      };
    },
    getByID(id) {
      axios
        .get("/transportador/getForRigthMenu/" + id)
        .then(({ data }) => {
          this.form = {
            id,
            name: data.nombre,
            email: data.email,
            info: data.information,
            shipper: data.shipper == 1 ? true : false,
            consignee: data.consignee == 1 ? true : false,
            carrier: data.carrier == 1 ? true : false
          };
          console.log(data);
        })
        .catch(error => {
          console.log(error);
        });
    },
    update() {
      axios
        .post("/updateFromRigthMenu/transport", this.form)
        .then(({ data }) => {
          bus.$emit("assignTransport", data);
          this.resetForm();
          bus.$emit("close");
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script> 