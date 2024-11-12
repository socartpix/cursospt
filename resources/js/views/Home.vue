// resources/js/views/Home.vue
<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">Bienvenido a la Plataforma de Cursos</h1>
    
    <div class="mb-6">
      <h2 class="text-2xl font-bold mb-4">Buscar Cursos</h2>
      <div class="flex gap-4 mb-4">
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Buscar por nombre..."
          class="border rounded p-2 flex-grow"
          @input="searchCourses"
        >
        <select 
          v-model="selectedCategory" 
          class="border rounded p-2"
          @change="searchCourses"
        >
          <option value="">Todas las categorías</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.nombre }}
          </option>
        </select>
        <select 
          v-model="selectedAgeGroup" 
          class="border rounded p-2"
          @change="searchCourses"
        >
          <option value="">Todos los rangos de edad</option>
          <option value="5-8">5-8 años</option>
          <option value="9-13">9-13 años</option>
          <option value="14-16">14-16 años</option>
          <option value="16+">16+ años</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="course in cursos" :key="course.id" class="border rounded-lg p-4">
        <h3 class="text-xl font-bold mb-2">{{ course.titulo }}</h3>
        <p class="mb-2">{{ course.descripcion }}</p>
        <div class="flex justify-between items-center mt-4">
          <span class="text-sm">{{ course.categoria.nombre }}</span>
          <router-link 
          :to="{ name: 'detalle-curso', params: { id: course.id }}" 
            class="bg-blue-500 text-white px-4 py-2 rounded"
          >
            Ver Curso
          </router-link>
        </div>
      </div>
    </div>

    <div v-if="isLoading" class="text-center my-4">
      Cargando...
    </div>

    <div v-if="error" class="text-red-500 text-center my-4">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const cursos = ref([]);
const categories = ref([]);
const searchQuery = ref('');
const selectedCategory = ref('');
const selectedAgeGroup = ref('');
const isLoading = ref(false);
const error = ref('');

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/v1/categorias');
    categories.value = response.data;
  } catch (err) {
    console.error('Error al cargar categorías:', err);
  }
};

const searchCourses = async () => {
    try {
        console.log('Iniciando búsqueda con params:', {
            nombre: searchQuery.value,
            categoria: selectedCategory.value,
            grupo_edad: selectedAgeGroup.value
        });
        
        isLoading.value = true;
        const response = await axios.get('/api/v1/cursos/buscar', {
            params: {
                nombre: searchQuery.value || '',
                categoria: selectedCategory.value || '',
                grupo_edad: selectedAgeGroup.value || ''
            }
        });
        
        console.log('Respuesta recibida:', response.data);
        cursos.value = response.data;
        
    } catch (err) {
        console.error('Error completo:', err);
        error.value = 'Error al cargar los cursos';
    } finally {
        isLoading.value = false;
    }
};


onMounted(() => {
  fetchCategories();
  searchCourses();
});
</script>