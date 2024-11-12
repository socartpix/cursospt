// resources/js/views/student/CourseSearch.vue
<template>
  <div class="max-w-7xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold mb-6">Explorar Cursos</h1>
      
      <!-- Filtros de búsqueda -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-4 rounded-lg shadow">
        <div>
          <input 
            v-model="filtros.nombre"
            type="text"
            placeholder="Buscar por nombre..."
            class="w-full border rounded p-2"
            @input="buscarCursos"
          >
        </div>
        <div>
          <select 
            v-model="filtros.categoria"
            class="w-full border rounded p-2"
            @change="buscarCursos"
          >
            <option value="">Todas las categorías</option>
            <option 
              v-for="categoria in categorias" 
              :key="categoria.id" 
              :value="categoria.id"
            >
              {{ categoria.nombre }}
            </option>
          </select>
        </div>
        <div>
          <select 
            v-model="filtros.edad"
            class="w-full border rounded p-2"
            @change="buscarCursos"
          >
            <option value="">Todos los rangos de edad</option>
            <option value="5-8">5-8 años</option>
            <option value="9-13">9-13 años</option>
            <option value="14-16">14-16 años</option>
            <option value="16+">16+ años</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Resultados -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="curso in cursos" 
        :key="curso.id"
        class="bg-white rounded-lg shadow overflow-hidden"
      >
        <div class="p-6">
          <h2 class="text-xl font-bold mb-2">{{ curso.titulo }}</h2>
          <p class="text-gray-600 mb-4 line-clamp-3">{{ curso.descripcion }}</p>
          
          <div class="flex gap-2 mb-4">
            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
              {{ curso.categoria.nombre }}
            </span>
            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">
              {{ curso.grupo_edad.rango }} años
            </span>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">
              {{ curso.videos_count }} videos
            </span>
            <router-link 
              :to="{ name: 'detalle-curso', params: { id: curso.id }}"
              class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
              Ver Curso
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Estado de carga -->
    <div 
      v-if="cargando" 
      class="text-center py-12"
    >
      Cargando cursos...
    </div>

    <!-- Mensaje si no hay resultados -->
    <div 
      v-if="!cargando && !cursos.length" 
      class="text-center py-12"
    >
      <p class="text-gray-600">No se encontraron cursos con los filtros seleccionados</p>
    </div>

    <!-- Paginación -->
    <div 
      v-if="totalPaginas > 1" 
      class="flex justify-center mt-8 gap-2"
    >
      <button 
        v-for="pagina in totalPaginas"
        :key="pagina"
        @click="cambiarPagina(pagina)"
        class="px-4 py-2 rounded"
        :class="{
          'bg-blue-500 text-white': paginaActual === pagina,
          'bg-gray-200': paginaActual !== pagina
        }"
      >
        {{ pagina }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const cursos = ref([]);
const categorias = ref([]);
const cargando = ref(false);
const paginaActual = ref(1);
const totalPaginas = ref(1);

const filtros = ref({
  nombre: '',
  categoria: '',
  edad: '',
});

// Cargar datos iniciales
const cargarCategorias = async () => {
  try {
    const response = await axios.get('/api/v1/categorias');
    categorias.value = response.data;
  } catch (error) {
    console.error('Error al cargar categorías:', error);
  }
};

// Buscar cursos
const buscarCursos = async () => {
  try {
    cargando.value = true;
    
    const params = {
      page: paginaActual.value,
      nombre: filtros.value.nombre,
      categoria: filtros.value.categoria,
      grupo_edad: filtros.value.edad,
    };

    const response = await axios.get('/api/v1/cursos/buscar', { params });
    cursos.value = response.data.data;
    totalPaginas.value = Math.ceil(response.data.total / response.data.per_page);

    // Actualizar URL con los filtros
    router.push({ 
      query: { 
        ...filtros.value,
        page: paginaActual.value 
      }
    });
  } catch (error) {
    console.error('Error al buscar cursos:', error);
  } finally {
    cargando.value = false;
  }
};

// Cambiar página
const cambiarPagina = (pagina) => {
  paginaActual.value = pagina;
  buscarCursos();
};

// Inicialización
onMounted(() => {
  // Recuperar filtros de la URL
  const { nombre, categoria, edad, page } = route.query;
  filtros.value = {
    nombre: nombre || '',
    categoria: categoria || '',
    edad: edad || '',
  };
  paginaActual.value = parseInt(page) || 1;

  cargarCategorias();
  buscarCursos();
});
</script>