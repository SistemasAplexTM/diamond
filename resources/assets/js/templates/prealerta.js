$(document).ready(function () {
  var id_agencia = $('#prealerta').data('id_agencia');
  $('#tbl-prealerta').DataTable({
    ajax: 'prealerta/' + id_agencia + '/all',
    columns: [{
      data: 'tracking',
      name: 'tracking'
    }, {
      data: 'despachar',
      name: 'despachar',
      "render": function (data, type, full, meta) {
        if (full.despachar == 1) {
          return '<span class="badge badge-primary">Despachar</span>';
        } else {
          return '<span class="badge badge-warning">Esperar</span>';
        }
      }
    }, {
      data: 'consignee',
      name: 'consignee_id'
    }, {
      data: 'agencia',
      name: 'agencia_id'
    }, {
      data: 'contenido',
      name: 'contenido'
    }, {
      data: 'instruccion',
      name: 'instruccion'
    }, {
      data: 'correo',
      name: 'correo'
    }, {
      data: 'telefono',
      name: 'telefono'
    }]
  });
  $('#despachar').change(function () {
    objVue.msn();
  });
});

var objVue = new Vue({
  el: '#prealerta',
  mounted: function () {
    let me = this;
  },
  data: {
    email: null,
    instruccion: null,
    tracking: null,
    contenido: null,
    declarado: null,
    telefono: null,
    existConsignee: false,
    despachar: false,
    dialogVisibleUpload: false,
    data: null,
    fileList: [],
    headersUpload: {
      'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content"),
    },
  },
  methods: {
    uploadFiles(file, fileList) {
      var l = Ladda.create(document.querySelector('.ladda-button'));
      l.start();
      // toastr.info('Cargando archivo archivo...');
      // toastr.options.closeButton = true;
      let me = this
      //Create new formData object
      const fd = new FormData();
      //append the file you want to upload
      fd.append("file", file.file);
      fd.append("agency_id", $('#agency_id').val());
      fd.append("module_record_id", this.data.id);
      fd.append("module_id", 23);

      //send call the api to upload files using axios or any other means
      axios.post(file.action, fd).then(function (req) {
        me.$refs.upload.clearFiles()
        // toastr.success('Archivo cargado correctamente');
        // toastr.options.closeButton = true;
        l.stop();
      }).catch(function (error) {
        l.stop();
        console.log(error);
        toastr.error("Error al cargar el archivo.", {
          timeOut: 30000
        });
      });
    },
    submitUpload() {
      this.$refs.upload.submit();
    },
    handleExceed(files, fileList) {
      this.$message.warning(
        `El límite es 1 archivo, haz cargado ${fileList.length} archivo esta vez`
      );
    },
    msn: function () {
      this.despachar = !this.despachar;
    },
    resetForm: function () {
      this.tracking = null;
      this.contenido = null;
      this.declarado = null;
      this.instruccion = null;
      this.errors.clear();
    },
    create: function () {
      const isUnique = (value) => {
        return axios.post($('#formPrealerta').data('id_agencia') + '/validar_tracking', {
          'element': value
        }).then((response) => {
          return {
            valid: response.data.valid,
            data: {
              message: response.data.message
            }
          };
        });
      };
      // The messages getter may also accept a third parameter that includes the data we returned earlier.
      this.$validator.extend('unique', {
        validate: isUnique,
        getMessage: (field, params, data) => {
          return data.message;
        }
      });
      this.$validator.validateAll().then((result) => {
        if (result) {
          var l = Ladda.create(document.querySelector('.ladda-button'));
          l.start();
          let me = this;
          axios.post($('#formPrealerta').data('id_agencia'), {
            'email': this.email,
            'instruccion': this.instruccion,
            'tracking': this.tracking,
            'contenido': this.contenido,
            'declarado': this.declarado,
            'despachar': $('#despachar').prop('checked'),
          }).then(function (response) {
            l.stop();
            if (response.data['code'] == 200) {
              me.data = response.data['data'];
              toastr.success('Su paquete ha sido prealertado');
              toastr.options.closeButton = true;
              setTimeout(() => {
                me.submitUpload();
                me.resetForm();
              }, 500);
            } else {
              console.log(response);
              toastr.warning('Error: ' + response.data['error']);
              toastr.options.closeButton = true;
            }
          }).catch(function (error) {
            l.stop();
            console.log(error);
            toastr.error("Error al prealertar.", {
              timeOut: 30000
            });
          });
        }
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error: Completa los campos.');
      });
    }
  },
});