// resources/js/views/CourseList.vue
<template>
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Cursos Disponibles</h1>

    <!-- Filtros -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input 
          v-model="filtros.nombre"
          type="text"
          placeholder="Buscar por nombre..."
          class="border rounded p-2"
        >
        <select 
          v-model="filtros.categoria"
          class="border rounded p-2"
        >
          <option value="">Todas las categorías</option>
        </select>
        <select 
          v-model="filtros.edad"
          class="border rounded p-2"
        >
          <option value="">Todas las edades</option>
          <option value="5-8">5-8 años</option>
          <option value="9-13">9-13 años</option>
          <option value="14-16">14-16 años</option>
          <option value="16+">16+ años</option>
        </select>
      </div>
    </div>

    <!-- Lista de Cursos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="curso in cursos" 
        :key="curso.id"
        class="bg-white rounded-lg shadow overflow-hidden"
      >
        <div class="p-6">
          <h2 class="text-xl font-bold mb-2">{{ curso.titulo }}</h2>
          <p class="text-gray-600 mb-4">{{ curso.descripcion }}</p>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">
              {{ curso.videos_count }} videos
            </span>
            <router-link 
              :to="{ name: 'detalle-curso', params: { id: curso.id }}"
              class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
              Ver Detalles
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Estado de carga -->
    <div v-if="cargando" class="text-center py-12">
      Cargando cursos...
    </div>

    <!-- Mensaje si no hay cursos -->
    <div v-if="!cargando && !cursos.length" class="text-center py-12">
      No se encontraron cursos
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const cursos = ref([]);
const cargando = ref(true);
const filtros = ref({
  nombre: '',
  categoria: '',
  edad: ''
});

const cargarCursos = async () => {
  try {
    cargando.value = true;
    const response = await axios.get('/api/v1/cursos');
    cursos.value = response.data;
  } catch (error) {
    console.error('Error al cargar cursos:', error);
  } finally {
    cargando.value = false;
  }
};

onMounted(() => {
  cargarCursos();
});
</script>