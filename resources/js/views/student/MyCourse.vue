// resources/js/views/student/MyCourses.vue
<template>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Mis Cursos</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="curso in cursos" 
        :key="curso.id"
        class="bg-white rounded-lg shadow overflow-hidden"
      >
        <div class="p-6">
          <h2 class="text-xl font-bold mb-2">{{ curso.titulo }}</h2>
          <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Progreso</span>
              <span>{{ curso.pivot.progreso }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
              <div 
                class="bg-blue-500 h-2.5 rounded-full"
                :style="{ width: `${curso.pivot.progreso}%` }"
              ></div>
            </div>
          </div>
          
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">
              {{ curso.videos_count }} videos
            </span>
            <router-link 
              :to="{ name: 'course-content', params: { id: curso.id }}"
              class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
              Continuar
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Mensaje si no hay cursos -->
    <div 
      v-if="!cursos.length"
      class="text-center py-12"
    >
      <p class="text-gray-600 mb-4">No estás inscrito en ningún curso</p>
      <router-link 
        to="/cursos"
        class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600"
      >
        Explorar Cursos
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const cursos = ref([]);

const cargarCursos = async () => {
  try {
    const response = await axios.get('/api/v1/mis-cursos');
    cursos.value = response.data;
  } catch (error) {
    console.error('Error al cargar cursos:', error);
  }
};

onMounted(() => {
  cargarCursos();
});
</script>