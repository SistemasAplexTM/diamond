<template>
  <el-drawer
    :visible.sync="drawer"
    direction="rtl"
    size="28%"
    :destroy-on-close="true"
    :append-to-body="true"
    :wrapperClosable="false"
    @close="close()"
  >
    <header-component slot="title" :data="data" @back="close()" />
    <transition-group name="fade">
      <component :is="component_active" :payload="data" key="component"></component>
      <footer-component key="footer" :edit="data.edit" :table="data.table" v-if="!data.btn_remove" />
    </transition-group>
  </el-drawer>
</template>
<script>
import { mapGetters } from "vuex";
import FooterComponent from "./Footer";
import HeaderComponent from "./Header";
export default {
  components: { FooterComponent, HeaderComponent },
  data() {
    return {
      component_active: "menu-component",
      drawer: false,
      data: {
        icon: "",
        title: "",
        btn_remove: false
      }
    };
  },
  created() {
    let me = this;
    bus.$on("open", function(payload) {
      me.data = payload;
      me.component_active = payload.component;
      me.drawer = true;
    });
    bus.$on("close", function() {
      me.component_active = null;
      me.drawer = false;
    });
  },
  methods: {
    close(){
      this.drawer = false;
      bus.$emit('close_form')
    }
  }
};
</script>
<style lang="css">
.el-drawer__body {
  margin-top: 80px;
  margin-bottom: 80px;
  padding-left: 20px;
  padding-right: 20px;
}
.el-drawer {
  overflow: auto !important;
}
.el-drawer__header {
  padding-top: 5px !important;
  padding-bottom: 5px !important;
  background-color: #5f8fdf !important;
  color: white;
  position: fixed;
  top: 0;
  width: 28%;
  margin-right: 0px;
  margin-left: 0px;
  z-index: 999;
}
.el-page-header__content {
  color: white !important;
}

@media (max-width: 1260px) {
  .el-drawer, .el-drawer__header {
    width: 40% !important;
  }
   .footerRight{
     width: 38.5% !important;
   }
}
@media (max-width: 920px) {
  .el-drawer, .el-drawer__header {
    width: 50% !important;
  }
   .footerRight{
     width: 48.5% !important;
   }
}
@media (max-width: 700px) {
  .el-drawer, .el-drawer__header {
    width: 70% !important;
  }
   .footerRight{
     width: 68.5% !important;
   }
}
@media (max-width: 490px) {
  .el-drawer, .el-drawer__header {
    width: 90% !important;
  }
   .footerRight{
     width: 88.5% !important;
   }
}
</style>
