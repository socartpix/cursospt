// resources/js/views/student/CourseContent.vue
<template>
  <div class="max-w-7xl mx-auto">
    <div v-if="curso" class="mb-6">
      <h1 class="text-3xl font-bold">{{ curso.titulo }}</h1>
      <p class="text-gray-600 mt-2">{{ curso.descripcion }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Lista de Videos -->
      <div class="lg:col-span-1 bg-white rounded-lg shadow p-4">
        <h2 class="font-bold mb-4">Contenido del Curso</h2>
        <div class="space-y-2">
          <button
            v-for="video in videos"
            :key="video.id"
            @click="seleccionarVideo(video)"
            class="w-full text-left p-2 rounded hover:bg-gray-100"
            :class="{
              'bg-blue-50 border-l-4 border-blue-500': videoActual?.id === video.id
            }"
          >
            <div class="flex items-center justify-between">
              <span>{{ video.titulo }}</span>
              <span 
                class="text-sm"
                :class="{
                  'text-green-500': getProgresoVideo(video) === 100,
                  'text-yellow-500': getProgresoVideo(video) > 0
                }"
              >
                {{ getProgresoVideo(video) }}%
              </span>
            </div>
          </button>
        </div>
      </div>

      <!-- Reproductor y Comentarios -->
      <div class="lg:col-span-3">
        <div v-if="videoActual" class="bg-white rounded-lg shadow">
          <!-- Reproductor -->
          <div class="aspect-w-16 aspect-h-9">
            <iframe 
              :src="getYoutubeEmbedUrl(videoActual.url_youtube)"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
              class="w-full h-full"
              @load="iniciarSeguimientoVideo"
            ></iframe>
          </div>

          <div class="p-6">
            <h2 class="text-2xl font-bold mb-2">{{ videoActual.titulo }}</h2>
            <p class="text-gray-600 mb-4">{{ videoActual.descripcion }}</p>

            <!-- Likes -->
            <div class="flex items-center gap-2 mb-6">
              <button 
                @click="toggleLike"
                class="flex items-center gap-1 px-3 py-1 rounded hover:bg-gray-100"
                :class="{ 'text-blue-500': hasLike }"
              >
                <span>👍</span>
                {{ likes }} Me gusta
              </button>
            </div>

            <!-- Comentarios -->
            <div class="border-t pt-4">
              <h3 class="font-bold mb-4">Comentarios</h3>
              
              <!-- Formulario de comentario -->
              <form @submit.prevent="enviarComentario" class="mb-6">
                <textarea 
                  v-model="nuevoComentario"
                  rows="3"
                  placeholder="Escribe un comentario..."
                  class="w-full border rounded-lg p-2 mb-2"
                  required
                ></textarea>
                <button 
                  type="submit"
                  class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                  :disabled="enviandoComentario"
                >
                  {{ enviandoComentario ? 'Enviando...' : 'Comentar' }}
                </button>
              </form>

              <!-- Lista de comentarios -->
              <div class="space-y-4">
                <div 
                  v-for="comentario in comentarios"
                  :key="comentario.id"
                  class="border-b pb-4"
                >
                  <div class="flex justify-between mb-2">
                    <span class="font-semibold">{{ comentario.usuario.name }}</span>
                    <span class="text-sm text-gray-500">
                      {{ formatFecha(comentario.created_at) }}
                    </span>
                  </div>
                  <p class="text-gray-700">{{ comentario.contenido }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const curso = ref(null);
const videos = ref([]);
const videoActual = ref(null);
const comentarios = ref([]);
const likes = ref(0);
const hasLike = ref(false);
const nuevoComentario = ref('');
const enviandoComentario = ref(false);
const progresos = ref({});

// Cargar datos del curso
const cargarCurso = async () => {
  try {
    const response = await axios.get(`/api/v1/cursos/${route.params.id}`);
    curso.value = response.data;
    cargarVideos();
  } catch (error) {
    console.error('Error al cargar curso:', error);
  }
};

// Cargar videos
const cargarVideos = async () => {
  try {
    const response = await axios.get(`/api/v1/cursos/${route.params.id}/videos`);
    videos.value = response.data;
    if (videos.value.length && !videoActual.value) {
      seleccionarVideo(videos.value[0]);
    }
  } catch (error) {
    console.error('Error al cargar videos:', error);
  }
};

// Seleccionar video
const seleccionarVideo = async (video) => {
  videoActual.value = video;
  cargarComentarios();
  cargarLikes();
};

// Cargar comentarios
const cargarComentarios = async () => {
  try {
    const response = await axios.get(`/api/v1/videos/${videoActual.value.id}/comentarios`);
    comentarios.value = response.data;
  } catch (error) {
    console.error('Error al cargar comentarios:', error);
  }
};

// Enviar comentario
const enviarComentario = async () => {
  if (!nuevoComentario.value.trim()) return;

  try {
    enviandoComentario.value = true;
    await axios.post(`/api/v1/videos/${videoActual.value.id}/comentar`, {
      contenido: nuevoComentario.value
    });
    nuevoComentario.value = '';
    await cargarComentarios();
  } catch (error) {
    console.error('Error al enviar comentario:', error);
  } finally {
    enviandoComentario.value = false;
  }
};

// Gestión de likes
const cargarLikes = async () => {
  try {
    const response = await axios.get(`/api/v1/videos/${videoActual.value.id}/likes`);
    likes.value = response.data.total;
    hasLike.value = response.data.hasLike;
  } catch (error) {
    console.error('Error al cargar likes:', error);
  }
};

const toggleLike = async () => {
  try {
    await axios.post(`/api/v1/videos/${videoActual.value.id}/like`);
    await cargarLikes();
  } catch (error) {
    console.error('Error al gestionar like:', error);
  }
};

// Seguimiento del progreso
const iniciarSeguimientoVideo = () => {
  // Aquí implementarías la lógica para seguir el progreso del video
  // usando el API de YouTube Player
};

const getProgresoVideo = (video) => {
  return progresos.value[video.id] || 0;
};

// Utilidades
const getYoutubeEmbedUrl = (url) => {
  const videoId = url.split('v=')[1];
  return `https://www.youtube.com/embed/${videoId}`;
};

const formatFecha = (fecha) => {
  return new Date(fecha).toLocaleDateString();
};

onMounted(() => {
  cargarCurso();
});
</script>