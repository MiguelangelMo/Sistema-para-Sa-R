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
          <i class="fa fa-align-justify"></i> Roles
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model = "criterio">
                  <option value="nombre">Nombre</option>
                  <option value="descripcion">Descripción</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                  @keyup.enter="listarRol(1, buscar, criterio)"
                />
                <button type="submit" class="btn btn-primary" @click="listarRol(1, buscar, criterio)">
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="roles in arrayRol" :key="roles.id">
                <td v-text="roles.nombre"></td>
                <td v-text="roles.descripcion"></td>
                <td>
                  <div v-if="roles.condicion">
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
                <a class="page-link" href="#" @click.prevent="cambiarPagina(current_page - 1, buscar, criterio)">Ant</a>
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
                <a class="page-link" href="#" @click.prevent="cambiarPagina(current_page + 1, buscar, criterio)">Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
  </main>
</template>

<script>
export default {
  data() {
    return {
      rol_id: 0,
      nombre: "",
      descripcion: "",
      arrayRol: [],
      paginacion: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: 'nombre',
      buscar: ''
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
    listarRol(page, buscar, criterio) {
      let me = this;
      let url = "/roles?page=" + page + '&buscar=' + buscar + '&criterio=' + criterio;
      axios
        .get(url)
        .then(function(respuesta) {
          var response = respuesta.data;
          me.arrayRol = response.rol.data;
          me.paginacion = response.paginacion;
        })
        .catch(function(respuesta) {
          console.log(respuesta);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.paginacion.current_page = page;
      me.listarRol(page, buscar, criterio);
    }
  },
  mounted() {
    this.listarRol(1, this.buscar, this.criterio);
  }
};
</script>
