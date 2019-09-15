<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Desktop</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Clientes
          <button
            type="button"
            class="btn btn-secondary"
            @click="abrirModal('cliente', 'registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="num_documento">Número Documento</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                  @keyup.enter="listarPersona(1, buscar, criterio)"
                />
                <button
                  type="submit"
                  class="btn btn-primary"
                  @click="listarPersona(1, buscar, criterio)"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Tipo Documento</th>
                <th>N° Documento</th>
                <th>Dirección</th>
                <th>Telefono</th>
                <th>E-Mail</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="persona in arrayPersona" :key="persona.id">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('cliente', 'actualizar', persona)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                </td>
                <td v-text="persona.nombre"></td>
                <td v-text="persona.tipo_documento"></td>
                <td v-text="persona.num_documento"></td>
                <td v-text="persona.direccion"></td>
                <td v-text="persona.telefono"></td>
                <td v-text="persona.email"></td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="paginacion.current_page > 1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(current_page - 1, buscar, criterio)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="paginations in pagesNumber"
                :key="paginations"
                :class="[paginations == isActived ? 'active' : ''] "
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(paginations, buscar, criterio)"
                  v-text="paginations"
                ></a>
              </li>
              <li class="page-item" v-if="paginacion.current_page < paginacion.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(current_page + 1, buscar, criterio)"
                >Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
      :class="{'mostrar':modal}"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombre de Persona</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nombre de categoría"
                    v-model="nombre"
                  />
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Tipo de Documento</label>
                <div class="col-md-9">
                  <select type="text" class="form-control" v-model="tipo_documento">
                    <option selected="selected" disabled="disabled">Seleccione</option>
                    <option value="DNI">DNI</option>
                    <option value="Juridica">Jurídica</option>
                    <option value="pasaporte">Pasaporte</option>
                  </select>
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">N° Documento</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="N° Documento"
                    v-model="num_documento"
                  />
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nombre de categoría"
                    v-model="direccion"
                  />
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                <div class="col-md-9">
                  <input
                    type="tel"
                    class="form-control"
                    placeholder="Nombre de categoría"
                    v-model="telefono"
                  />
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">E-Mail</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    class="form-control"
                    placeholder="Ingrese su E-Mail"
                    v-model="email"
                  />
                </div>
              </div>
              <div v-show="errorPersona" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="errores in errorMostrarMsjPersona" :key="errores" v-text="errores"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tituloAccion==1"
              @click="registrarPersona()"
            >Guardar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tituloAccion==2"
              @click="actualizarPersona()"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
export default {
  data() {
    return {
      cliente_id: 0,
      nombre: "",
      tipo_documento: "",
      num_documento: "",
      direccion: "",
      telefono: "",
      email: "",
      arrayPersona: [],
      modal: 0,
      tituloModal: "",
      tituloAccion: 0,
      errorPersona: 0,
      errorMostrarMsjPersona: [],
      paginacion: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
      buscar: ""
    };
  },
  computed: {
    isActived: function() {
      return this.paginacion.current_page;
    },
    pagesNumber: function() {
      if (!this.paginacion.to) {
        return [];
      }

      var from = this.paginacion.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.paginacion.last_page) {
        to = this.paginacion.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  methods: {
    listarPersona(page, buscar, criterio) {
      let me = this;
      let url =
        "/cliente?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(respuesta) {
          var response = respuesta.data;
          me.arrayPersona = response.persona.data;
          me.paginacion = response.paginacion;
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.paginacion.current_page = page;
      me.listarPersona(page, buscar, criterio);
    },
    registrarPersona() {
      if (this.validarPersona()) {
        return;
      }

      let me = this;

      axios
        .post("/cliente/registrar", {
          nombre: me.nombre,
          tipo_documento: me.tipo_documento,
          num_documento: me.num_documento,
          direccion: me.direccion,
          telefono: me.telefono,
          email: me.email
        })
        .then(function(respuesta) {
          me.cerrarModal();
          me.listarPersona(1, "", "nombre");
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    actualizarPersona() {
      if (this.validarPersona()) {
        return;
      }

      let me = this;

      axios
        .put("/cliente/actualizar", {
          id: me.cliente_id,
          nombre: me.nombre,
          tipo_documento: me.tipo_documento,
          num_documento: me.num_documento,
          direccion: me.direccion,
          telefono: me.telefono,
          email: me.email
        })
        .then(function(respuesta) {
          me.cerrarModal();
          me.listarPersona(1, "", "nombre");
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    validarPersona() {
      this.errorPersona = 0;
      this.errorMostrarMsjPersona = [];

      if (!this.nombre)
        this.errorMostrarMsjPersona.push(
          "¡Error el campo nombre no puede estar vacio!"
        );
      if (!this.tipo_documento)
        this.errorMostrarMsjPersona.push(
          "¡Error el campo Tipo Documento no puede estar vacio!"
        );
      if (!this.num_documento)
        this.errorMostrarMsjPersona.push(
          "¡Error el campo N° Documento no puede estar vacio!"
        );

      if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

      return this.errorPersona;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "cliente":
          switch (accion) {
            case "registrar":
              this.modal = 1;
              this.tituloModal = "Registrar Persona";
              this.nombre = "";
              this.tipo_documento = "";
              this.num_documento = "";
              this.direccion = "";
              this.telefono = "";
              this.email = "";
              this.tituloAccion = 1;
              break;
            case "actualizar":
              console.log(data);
              this.tituloModal = "Actualizar Persona";
              this.modal = 1;
              this.tituloAccion = 2;
              this.cliente_id = data["id"];
              this.nombre = data["nombre"];
              this.tipo_documento = data["tipo_documento"];
              this.num_documento = data["num_documento"];
              this.direccion = data["direccion"];
              this.telefono = data["telefono"];
              this.email = data["email"];
              break;
          }
          break;
      }
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.tipo_documento = "";
      this.num_documento = "";
      this.direccion = "";
      this.telefono = "";
      this.email = "";
    }
  },
  mounted() {
    this.listarPersona(1, this.buscar, this.criterio);
  }
};
</script>
<style>
.mostrar-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  opacity: 1 !important;
  background-color: #3c29297a !important;
  position: absolute !important;
  top: 3em !important;
  display: list-item !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red;
  font-weight: bold;
}
</style>
