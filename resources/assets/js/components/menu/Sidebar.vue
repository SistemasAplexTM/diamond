<template>
  <ul class="nav metismenu" id="side-menu">
    <slot name="header"></slot>
    <li
      v-for="item in menu"
      class="active"
      :key="item.id"
    >
      <a
        :href="item.route"
        :style="'background-color: ' + formatMeta(item.meta, 'color')"
        style="color: white"
      >
        <i :class="formatMeta(item.meta, 'icon')"></i>
        <span class="nav-label">{{ item.name }}</span>
        <span class="arrow">
          <i class="fal fa-angle-down"></i>
        </span>
      </a>
      <ul class="nav nav-second-level">
        <li v-for="subItem in item.children" :key="subItem.id">
          <a :href="'/' + subItem.route">
            <span :class="formatMeta(subItem.meta, 'icon')"></span>
            {{ subItem.name }}
          </a>
        </li>
      </ul>
    </li>
  </ul>
</template>

<script>
export default {
  data() {
    return {
      menu: [],
      user: "",
    };
  },
  created() {
    let me = this;
    me.get();
    bus.$on("refreshList", function (payload) {
      localStorage.removeItem("menu");
      me.get();
    });
  },
  methods: {
    get() {
      let user = localStorage.getItem("user");
      if (user) {
        this.user = JSON.parse(user);
      }
      let menuLS = localStorage.getItem("menu");
      if (menuLS) {
        this.menu = JSON.parse(menuLS);
      } else {
        axios
          .get("/getMenu/" + true + "/17")
          .then(({ data }) => {
            if(!user){
              this.user = data.user
            }
            this.menu = data.menu;
            localStorage.setItem("user", JSON.stringify(data.user));
            localStorage.setItem("menu", JSON.stringify(data.menu));
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    formatMeta(meta, param) {
      if (meta) {
        var data = JSON.parse(meta);
        return data[param];
      }
    },
    verifyRole(item) {
      console.log("user", this.user.roles);
      console.log("show item", item);
      return true;
    },
  },
};
</script>

<style lang="css">
.nav-header {
  padding-bottom: 0px;
}
</style>
