<template lang="html">
  <el-row :gutter="24" class="footerRight">
    <el-col :span="12" class="first">
      <el-button :type="(edit) ? 'warning' : 'success'" class="w-100" @click="selectOption(option)">
        <i :class="(edit) ? 'fal fa-edit' : 'fal fa-save'"></i>
        <el-divider direction="vertical"></el-divider>
        {{ (edit) ? 'Actualziar' : 'Guardar' }}
      </el-button>
    </el-col>
    <el-col :span="12" v-if="!edit">
      <el-button  class="w-100" @click="selectOption('cancel')">
        <i class="fal fa-times-circle"></i>
        <el-divider direction="vertical"></el-divider>
        Cancelar
      </el-button>
    </el-col>
    <el-col :span="6" v-if="table == 'invoice' && edit">
      <el-tooltip content="Ver PDF" placement="top">
        <el-button type="danger" class="w-100" @click="selectOption('pdf')">
          <i class="fal fa-file-pdf fa-lg"></i>
        </el-button>
      </el-tooltip>
    </el-col>
    <el-col :span="6" v-if="table == 'invoice' && edit">
      <el-tooltip content="Enviar Email" placement="top">
        <el-button v-if="table == 'invoice'" type="primary" class="w-100" @click="selectOption('email')">
          <i class="fal fa-envelope-open-text fa-lg"></i>
        </el-button>
      </el-tooltip>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: "FooterComponent",
  props: ["edit", "table"],
  computed: {
    option() {
      return this.edit ? "update" : "save";
    }
  },
  methods: {
    selectOption(type) {
      bus.$emit(type);
    }
  }
};
</script>

<style lang="css" scoped>
.footerRight {
  position: fixed;
  bottom: 0;
  padding: 10px 20px;
  width: 26.5%;
  margin-right: 0px;
  margin-left: 0px;
  border-top: solid 1px #dcdfe6;
  background-color: white;
  z-index: 3;
}
.first {
  border-right: solid 1px #dcdfe6;
}
</style>
