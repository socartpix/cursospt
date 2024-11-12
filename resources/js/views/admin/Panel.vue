// resources/js/views/admin/Dashboard.vue
<template>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Panel de Administración</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-2">Cursos</h3>
        <p class="text-gray-600 mb-4">Total: {{ estadisticas.totalCursos }}</p>
        <router-link 
          to="/admin/cursos" 
          class="text-blue-500 hover:underline"
        >
          Administrar Cursos
        </router-link>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-2">Estudiantes</h3>
        <p class="text-gray-600 mb-4">Total: {{ estadisticas.totalEstudiantes }}</p>
        <router-link 
          to="/admin/estudiantes" 
          class="text-blue-500 hover:underline"
        >
          Ver Estudiantes
        </router-link>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-2">Comentarios</h3>
        <p class="text-gray-600 mb-4">Pendientes: {{ estadisticas.comentariosPendientes }}</p>
        <router-link 
          to="/admin/comentarios" 
          class="text-blue-500 hover:underline"
        >
          Moderar Comentarios
        </router-link>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Últimos Cursos -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Últimos Cursos</h2>
        <div class="space-y-4">
          <div 
            v-for="curso in ultimosCursos" 
            :key="curso.id"
            class="border-b pb-4"
          >
            <h3 class="font-semibold">{{ curso.titulo }}</h3>
            <p class="text-sm text-gray-600">
              Estudiantes: {{ curso.total_estudiantes }}
            </p>
          </div>
        </div>
      </div>

      <!-- Últimos Comentarios -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Últimos Comentarios</h2>
        <div class="space-y-4">
          <div 
            v-for="comentario in ultimosComentarios" 
            :key="comentario.id"
            class="border-b pb-4"
          >
            <div class="flex justify-between mb-2">
              <span class="font-semibold">{{ comentario.usuario.name }}</span>
              <span class="text-sm text-gray-500">
                {{ new Date(comentario.created_at).toLocaleDateString() }}
              </span>
            </div>
            <p class="text-sm">{{ comentario.contenido }}</p>
            <div class="flex gap-2 mt-2">
              <button 
                @click="aprobarComentario(comentario.id)"
                class="text-green-500 text-sm hover:underline"
              >
                Aprobar
              </button>
              <button 
                @click="rechazarComentario(comentario.id)"
                class="text-red-500 text-sm hover:underline"
              >
                Rechazar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const estadisticas = ref({
  totalCursos: 0,
  totalEstudiantes: 0,
  comentariosPendientes: 0
});

const ultimosCursos = ref([]);
const ultimosComentarios = ref([]);

const cargarEstadisticas = async () => {
  try {
    const response = await axios.get('/api/v1/admin/estadisticas');
    estadisticas.value = response.data;
  } catch (error) {
    console.error('Error al cargar estadísticas:', error);
  }
};

const cargarUltimosCursos = async () => {
  try {
    const response = await axios.get('/api/v1/admin/ultimos-cursos');
    ultimosCursos.value = response.data;
  } catch (error) {
    console.error('Error al cargar últimos cursos:', error);
  }
};

const cargarUltimosComentarios = async () => {
  try {
    const response = await axios.get('/api/v1/admin/ultimos-comentarios');
    ultimosComentarios.value = response.data;
  } catch (error) {
    console.error('Error al cargar últimos comentarios:', error);
  }
};

const aprobarComentario = async (comentarioId) => {
  try {
    await axios.put(`/api/v1/admin/comentarios/${comentarioId}/aprobar`);
    cargarUltimosComentarios();
    cargarEstadisticas();
  } catch (error) {
    console.error('Error al aprobar comentario:', error);
  }
};

const rechazarComentario = async (comentarioId) => {
  try {
    await axios.put(`/api/v1/admin/comentarios/${comentarioId}/rechazar`);
    cargarUltimosComentarios();
    cargarEstadisticas();
  } catch (error) {
    console.error('Error al rechazar comentario:', error);
  }
};

onMounted(() => {
  cargarEstadisticas();
  cargarUltimosCursos();
  cargarUltimosComentarios();
});
</script>