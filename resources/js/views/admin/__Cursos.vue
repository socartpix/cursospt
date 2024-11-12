<template>
    <div class="max-w-7xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Administración de Cursos</h1>
        <button 
          @click="showModal = true"
          class="bg-blue-500 text-white px-4 py-2 rounded"
        >
          Crear Nuevo Curso
        </button>
      </div>
  
      <!-- Lista de Cursos -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo Edad</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Videos</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiantes</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="curso in cursos" :key="curso.id">
              <td class="px-6 py-4">{{ curso.titulo }}</td>
              <td class="px-6 py-4">{{ curso.categoria?.nombre }}</td>
              <td class="px-6 py-4">{{ curso.grupo_edad?.rango }}</td>
              <td class="px-6 py-4">{{ curso.videos_count }}</td>
              <td class="px-6 py-4">{{ curso.estudiantes_count }}</td>
              <td class="px-4 py-2">
                <button 
                  @click="editarCurso(curso)"
                  class="text-blue-500 hover:text-blue-700 mr-2"
                >
                  Editar
                </button>
                <button 
                  @click="gestionarVideos(curso.id)"
                  class="text-green-500 hover:text-green-700 mr-2"
                >
                  Gestionar Videos
                </button>
                <button 
                  @click="eliminarCurso(curso.id)"
                  class="text-red-500 hover:text-red-700"
                >
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Modal de Curso -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl">
          <h2 class="text-2xl font-bold mb-4">
            {{ cursoEnEdicion ? 'Editar Curso' : 'Crear Nuevo Curso' }}
          </h2>
          
          <form @submit.prevent="guardarCurso" class="space-y-4">
            <div>
              <label class="block mb-1">Título</label>
              <input 
                v-model="formData.titulo" 
                type="text" 
                class="w-full border rounded p-2"
                required
              >
            </div>
  
            <div>
              <label class="block mb-1">Descripción</label>
              <textarea 
                v-model="formData.descripcion" 
                class="w-full border rounded p-2"
                rows="4"
                required
              ></textarea>
            </div>
  
            <div>
              <label class="block mb-1">Categoría</label>
              <select 
                v-model="formData.categoria_id" 
                class="w-full border rounded p-2"
                required
              >
                <option value="">Seleccionar categoría</option>
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
              <label class="block mb-1">Grupo de Edad</label>
              <select 
                v-model="formData.grupo_edad_id" 
                class="w-full border rounded p-2"
                required
              >
                <option value="">Seleccionar grupo de edad</option>
                <option 
                  v-for="grupo in gruposEdad" 
                  :key="grupo.id" 
                  :value="grupo.id"
                >
                  {{ grupo.rango }}
                </option>
              </select>
            </div>
  
            <div class="flex justify-end space-x-2">
              <button 
                type="button"
                @click="showModal = false"
                class="px-4 py-2 border rounded"
              >
                Cancelar
              </button>
              <button 
                type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded"
                :disabled="isLoading"
              >
                {{ isLoading ? 'Guardando...' : 'Guardar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
  
      <!-- Modal de Gestión de Videos -->
      <div v-if="showVideosModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-3xl h-3/4 overflow-y-auto">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Videos del Curso</h2>
            <button 
              @click="agregarVideo"
              class="bg-blue-500 text-white px-4 py-2 rounded"
            >
              Agregar Video
            </button>
          </div>
  
          <div class="space-y-4">
            <div 
              v-for="video in videos" 
              :key="video.id"
              class="border rounded p-4"
            >
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-semibold">{{ video.titulo }}</h3>
                  <p class="text-sm text-gray-600">{{ video.url_youtube }}</p>
                </div>
                <div class="flex space-x-2">
                  <button 
                    @click="editarVideo(video)"
                    class="text-blue-500 hover:underline"
                  >
                    Editar
                  </button>
                  <button 
                    @click="eliminarVideo(video.id)"
                    class="text-red-500 hover:underline"
                  >
                    Eliminar
                  </button>
                </div>
              </div>
            </div>
          </div>
  
          <button 
            @click="showVideosModal = false"
            class="mt-4 px-4 py-2 border rounded"
          >
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
  
  // Estados
  const router = useRouter();
  const cursos = ref([]);
  const categorias = ref([]);
  const gruposEdad = ref([]);
  const videos = ref([]);
  const showModal = ref(false);
  const showVideosModal = ref(false);
  const isLoading = ref(false);
  const cursoEnEdicion = ref(null);
  const cursoSeleccionado = ref(null);
  
  const formData = ref({
    titulo: '',
    descripcion: '',
    categoria_id: '',
    grupo_edad_id: ''
  });
  
  // Cargar datos iniciales
  const cargarCursos = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/v1/cursos');
    cursos.value = response.data;
    console.log("cursos")
    console.log(cursos.value )
  } catch (err) {
    console.error('Error al cargar los cursos:', err);
    error.value = 'Error al cargar los cursos';
  } finally {
    isLoading.value = false;
  }
};
  
  const cargarCategorias = async () => {
    try {
      const response = await axios.get('/api/v1/categorias');
      categorias.value = response.data;
    } catch (error) {
      console.error('Error al cargar categorías:', error);
    }
  };
  
  const cargarGruposEdad = async () => {
    try {
      const response = await axios.get('/api/v1/grupos-edad');
      gruposEdad.value = response.data;
    } catch (error) {
      console.error('Error al cargar grupos de edad:', error);
    }
  };
  
  // Gestión de cursos
  const editarCurso = (curso) => {
    cursoEnEdicion.value = curso;
    formData.value = { ...curso };
    showModal.value = true;
  };
  
  const guardarCurso = async () => {
    try {
      isLoading.value = true;
      if (cursoEnEdicion.value) {
        await axios.put(`/api/v1/admin/cursos/${cursoEnEdicion.value.id}`, formData.value);
      } else {
        await axios.post('/api/v1/admin/cursos', formData.value);
      }
      await cargarCursos();
      showModal.value = false;
      resetForm();
    } catch (error) {
      console.error('Error al guardar curso:', error);
    } finally {
      isLoading.value = false;
    }
  };
  
  const confirmarEliminacion = async (curso) => {
    if (confirm('¿Estás seguro de que deseas eliminar este curso?')) {
      try {
        await axios.delete(`/api/v1/admin/cursos/${curso.id}`);
        await cargarCursos();
      } catch (error) {
        console.error('Error al eliminar curso:', error);
      }
    }
  };
  
  // Gestión de videos
  const gestionarVideos = async (curso) => {
    cursoSeleccionado.value = curso;
    showVideosModal.value = true;
    await cargarVideos(curso.id);
  };
  
  const cargarVideos = async (cursoId) => {
    try {
      const response = await axios.get(`/api/v1/admin/cursos/${cursoId}/videos`);
      videos.value = response.data;
    } catch (error) {
      console.error('Error al cargar videos:', error);
    }
  };
  
  const agregarVideo = () => {
    // Implementar lógica para agregar video
  };
  
  const editarVideo = (video) => {
    // Implementar lógica para editar video
  };
  
  const eliminarVideo = async (videoId) => {
    if (confirm('¿Estás seguro de que deseas eliminar este video?')) {
      try {
        await axios.delete(`/api/v1/admin/videos/${videoId}`);
        await cargarVideos(cursoSeleccionado.value.id);
      } catch (error) {
        console.error('Error al eliminar video:', error);
      }
    }
  };
  
  // Gestión de estudiantes
  const verEstudiantes = (curso) => {
    // Implementar navegación a la vista de estudiantes del curso
  };
  
  // Utilidades
  const resetForm = () => {
    formData.value = {
      titulo: '',
      descripcion: '',
      categoria_id: '',
      grupo_edad_id: ''
    };
    cursoEnEdicion.value = null;
  };
  
  // Inicialización
  onMounted(() => {
    cargarCursos();
    // cargarCategorias();
    // cargarGruposEdad();
  });
  </script>