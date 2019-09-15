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
          <i class="fa fa-align-justify"></i> Artículos
          <button
            type="button"
            class="btn btn-secondary"
            @click="abrirModal('articulo', 'registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" @click="cargarPDF()" class="btn btn-info">
            <i class="icon-doc"></i>&nbsp;Reporte
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="codigo">Codigo</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                  @keyup.enter="listarArticulo(1, buscar, criterio)"
                />
                <button
                  type="submit"
                  class="btn btn-primary"
                  @click="listarArticulo(1, buscar, criterio)"
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
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="articulo in arrayArticulos" :key="articulo.id">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('articulo', 'actualizar', articulo)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                  <template v-if="articulo.condicion">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="desactivarArticulo(articulo.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarArticulo(articulo.id)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                </td>
                <td v-text="articulo.codigo"></td>
                <td v-text="articulo.nombre"></td>
                <td v-text="articulo.nombre_categoria"></td>
                <td v-text="articulo.precio_venta"></td>
                <td
                  class="text-center badge"
                  :class="[articulo.stock < 15 ? 'badge-danger' : 'badge-primary'] "
                  v-text="articulo.stock"
                ></td>
                <td v-text="articulo.descripcion"></td>
                <td>
                  <div v-if="articulo.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivo</span>
                  </div>
                </td>
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
                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                <div class="col-md-9">
                  <select class="form-control" v-model="id_categoria">
                    <option value="0" disabled="disabled" selected="selected">Seleccione</option>
                    <option
                      v-for="categoria in arrayCategoria"
                      :key="categoria.id"
                      :value="categoria.id"
                      v-text="categoria.nombre"
                    ></option>
                  </select>
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre">Codigo</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Codigo Barra"
                    v-model="codigo"
                  />
                  <barcode :value="codigo" options="{ format : 'EAN-13' }">Generando Codigo</barcode>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nombre Artículo"
                    v-model="nombre"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre">Precio Venta</label>
                <div class="col-md-9">
                  <input type="number" class="form-control" v-model="precio_venta" placeholder />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre">Stock</label>
                <div class="col-md-9">
                  <input type="number" class="form-control" v-model="stock" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Ingrese la descripción"
                    v-model="descripcion"
                  />
                </div>
              </div>
              <div v-show="errorArticulo" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="errores in errorMostrarMsjArticulo" :key="errores" v-text="errores"></div>
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
              @click="registrarArticulo()"
            >Guardar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tituloAccion==2"
              @click="actualizarArticulo()"
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
import VueBarcode from "vue-barcode";
export default {
  data() {
    return {
      articulo_id: 0,
      id_categoria: 0,
      nombre_categoria: "",
      codigo: "",
      nombre: "",
      precio_venta: 0,
      stock: 0,
      descripcion: "",
      arrayArticulos: [],
      modal: 0,
      tituloModal: "",
      tituloAccion: 0,
      errorArticulo: 0,
      errorMostrarMsjArticulo: [],
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
      buscar: "",
      arrayCategoria: []
    };
  },
  components: {
    barcode: VueBarcode
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
    listarArticulo(page, buscar, criterio) {
      let me = this;
      let url =
        "/articulo?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(respuesta) {
          var response = respuesta.data;
          me.arrayArticulos = response.articulo.data;
          me.paginacion = response.paginacion;
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    cargarPDF() {
      window.open("http://localhost:8000/articulo/listarPdf", "_black");
    },
    selectCategoria() {
      let me = this;
      let url = "/categoria/n@m1re_ca2egor1a";
      axios
        .get(url)
        .then(function(respuesta) {
          //console.log(respuesta);
          var response = respuesta.data;
          me.arrayCategoria = response.categoria;
          // me.paginacion = response.paginacion;
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.paginacion.current_page = page;
      me.listarArticulo(page, buscar, criterio);
    },
    registrarArticulo() {
      if (this.validarArticulo()) {
        return;
      }

      let me = this;

      axios
        .post("/articulo/registrar", {
          id_categoria: me.id_categoria,
          codigo: me.codigo,
          nombre: me.nombre,
          precio_venta: me.precio_venta,
          stock: me.stock,
          descripcion: me.descripcion
        })
        .then(function(respuesta) {
          me.cerrarModal();
          me.listarArticulo(1, "", "nombre");
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    actualizarArticulo() {
      if (this.validarArticulo()) {
        return;
      }

      let me = this;

      axios
        .put("/articulo/actualizar", {
          id_categoria: me.id_categoria,
          codigo: me.codigo,
          nombre: me.nombre,
          precio_venta: me.precio_venta,
          stock: me.stock,
          descripcion: me.descripcion,
          id: me.articulo_id
        })
        .then(function(respuesta) {
          me.cerrarModal();
          me.listarArticulo(1, "", "nombre");
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    desactivarArticulo(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Esta Seguro de Desactivar la Artículo?",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si lo estoy!",
          cancelButtonText: "No lo estoy!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            axios
              .put("/articulo/desactivar", {
                id: id
              })
              .then(function(respuesta) {
                me.listarArticulo(1, "", "nombre");
                swalWithBootstrapButtons.fire(
                  "Desactivar!",
                  "Sea desactivado tu registro exitosamente.",
                  "success"
                );
              })
              .catch(function(respuesta) {
                console.log(respuesta);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
          }
        });
    },
    activarArticulo(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Esta Seguro de Activar la Artículo?",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si lo estoy!",
          cancelButtonText: "No lo estoy!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            axios
              .put("/articulo/activar", {
                id: id
              })
              .then(function(respuesta) {
                me.listarArticulo(1, "", "nombre");
                swalWithBootstrapButtons.fire(
                  "Activar!",
                  "Sea Activado tu registro exitosamente.",
                  "success"
                );
              })
              .catch(function(respuesta) {
                console.log(respuesta);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
          }
        });
    },
    validarArticulo() {
      this.errorArticulo = 0;
      this.errorMostrarMsjArticulo = [];

      if (this.id_categoria == 0)
        this.errorMostrarMsjArticulo.push(
          "¡Error el campo Categoría no puede estar vacio!"
        );
      if (!this.stock)
        this.errorMostrarMsjArticulo.push(
          "¡Error el campo Stock no puede ser menor que cero!"
        );
      if (!this.nombre)
        this.errorMostrarMsjArticulo.push(
          "¡Error el campo Nombre  no puede estar vacio!"
        );
      if (!this.codigo)
        this.errorMostrarMsjArticulo.push(
          "¡Error el campo Codigo no puede estar vacio!"
        );

      if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

      return this.errorArticulo;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "articulo":
          switch (accion) {
            case "registrar":
              this.modal = 1;
              this.tituloModal = "Registrar Artículo";
              this.id_categoria = 0;
              this.codigo = "";
              this.nombre = "";
              this.precio_venta = 0;
              this.stock = 0;
              this.descripcion = "";
              this.tituloAccion = 1;
              break;
            case "actualizar":
              //console.log(data);
              this.tituloModal = "Actualizar Modal";
              this.modal = 1;
              this.tituloAccion = 2;
              this.articulo_id = data["id"];
              this.id_categoria = data["id_categoria"];
              this.codigo = data["codigo"];
              this.nombre = data["nombre"];
              this.precio_venta = data["precio_venta"];
              this.stock = data["stock"];
              this.descripcion = data["descripcion"];
              break;
          }
          break;
      }
      this.selectCategoria();
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.id_categoria = 0;
      this.nombre_categoria = "";
      this.codigo = "";
      this.nombre = "";
      this.precio_venta = 0;
      this.stock = 0;
      this.descripcion = "";
      this.errorArticulo = "";
    }
  },
  mounted() {
    this.listarArticulo(1, this.buscar, this.criterio);
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
