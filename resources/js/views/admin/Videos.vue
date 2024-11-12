// resources/js/views/admin/Videos.vue
<template>
  <div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Videos del Curso: {{ curso?.titulo }}</h1>
      <button @click="showModal = true" class="bg-blue-500 text-white px-4 py-2 rounded">
        Agregar Video
      </button>
    </div>

    <div class="bg-white rounded-lg shadow">
      <div class="grid gap-4 p-4">
        <div v-for="video in videos" :key="video.id" class="border p-4 rounded">
          <div class="flex justify-between">
            <div>
              <h3 class="font-bold">{{ video.titulo }}</h3>
              <p class="text-gray-600">{{ video.descripcion }}</p>
              <p class="text-sm text-blue-500">{{ video.url_youtube }}</p>
            </div>
            <div class="flex gap-2">
              <button @click="editarVideo(video)" class="text-blue-500">Editar</button>
              <button @click="eliminarVideo(video.id)" class="text-red-500">Eliminar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Video -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold mb-4">{{ videoEnEdicion ? 'Editar Video' : 'Nuevo Video' }}</h2>
        
        <form @submit.prevent="guardarVideo">
          <div class="space-y-4">
            <div>
              <label class="block mb-1">Título</label>
              <input v-model="formData.titulo" type="text" required class="w-full border rounded p-2">
            </div>

            <div>
              <label class="block mb-1">URL de YouTube</label>
              <input v-model="formData.url_youtube" type="url" required class="w-full border rounded p-2">
            </div>

            <div>
              <label class="block mb-1">Descripción</label>
              <textarea v-model="formData.descripcion" required class="w-full border rounded p-2" rows="3"></textarea>
            </div>

            <div>
              <label class="block mb-1">Categoría</label>
              <select v-model="formData.categoria_id" required class="w-full border rounded p-2">
                <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                  {{ cat.nombre }}
                </option>
              </select>
            </div>
          </div>

          <div class="flex justify-end gap-2 mt-4">
            <button type="button" @click="showModal = false" class="px-4 py-2 border rounded">
              Cancelar
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const videos = ref([]);
const categorias = ref([]);
const curso = ref(null);
const showModal = ref(false);
const videoEnEdicion = ref(null);
const formData = ref({
  titulo: '',
  url_youtube: '',
  descripcion: '',
  categoria_id: '',
});

const cargarVideos = async () => {
  const response = await axios.get(`/api/v1/admin/cursos/${route.params.id}/videos`);
  videos.value = response.data;
};

const cargarCurso = async () => {
  const response = await axios.get(`/api/v1/admin/cursos/${route.params.id}`);
  curso.value = response.data;
};

const cargarCategorias = async () => {
  const response = await axios.get('/api/v1/categorias');
  categorias.value = response.data;
};

const guardarVideo = async () => {
  try {
    if (videoEnEdicion.value) {
      await axios.put(`/api/v1/admin/videos/${videoEnEdicion.value.id}`, formData.value);
    } else {
      await axios.post(`/api/v1/admin/cursos/${route.params.id}/videos`, formData.value);
    }
    await cargarVideos();
    showModal.value = false;
    resetForm();
  } catch (error) {
    console.error('Error al guardar video:', error);
  }
};

const editarVideo = (video) => {
  videoEnEdicion.value = video;
  formData.value = { ...video };
  showModal.value = true;
};

const eliminarVideo = async (id) => {
  if (confirm('¿Estás seguro de eliminar este video?')) {
    await axios.delete(`/api/v1/admin/videos/${id}`);
    await cargarVideos();
  }
};

const resetForm = () => {
  videoEnEdicion.value = null;
  formData.value = {
    titulo: '',
    url_youtube: '',
    descripcion: '',
    categoria_id: '',
  };
};

onMounted(() => {
  cargarCurso();
  cargarVideos();
  cargarCategorias();
});
</script>