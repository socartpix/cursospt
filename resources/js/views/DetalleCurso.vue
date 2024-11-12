// resources/js/views/CourseDetail.vue
<template>
  <div v-if="curso" class="max-w-4xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold mb-4">{{ curso.titulo }}</h1>
      <p class="text-gray-600 mb-4">{{ curso.descripcion }}</p>
      
      <div class="flex gap-4 mb-4">
        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded">
          {{ curso.categoria.nombre }}
        </span>
        <span class="bg-green-100 text-green-800 px-3 py-1 rounded">
          Edad: {{ curso.grupo_edad.rango }}
        </span>
      </div>

      <button 
        v-if="!inscrito"
        @click="inscribirseEnCurso" 
        class="bg-blue-500 text-white px-4 py-2 rounded"
        :disabled="isLoading"
      >
        {{ isLoading ? 'Procesando...' : 'Inscribirse al Curso' }}
      </button>
    </div>

    <div v-if="inscrito" class="mb-8">
      <h2 class="text-2xl font-bold mb-4">Contenido del Curso</h2>
      
      <div class="grid grid-cols-1 gap-4">
        <div v-for="video in videos" :key="video.id" class="border rounded p-4">
          <div class="flex justify-between items-center">
            <h3 
              class="text-lg font-semibold cursor-pointer"
              @click="seleccionarVideo(video)"
              :class="{'text-blue-500': videoActual?.id === video.id}"
            >
              {{ video.titulo }}
            </h3>
            <span 
              class="text-sm"
              :class="getProgresoClase(video.id)"
            >
              {{ getProgresoTexto(video.id) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Reproductor de Video -->
    <div v-if="videoActual && inscrito" class="mb-8">
      <div class="aspect-w-16 aspect-h-9 mb-4">
        <iframe 
          :src="getYoutubeEmbedUrl(videoActual.url_youtube)"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          class="w-full h-full"
          @load="iniciarSeguimientoVideo"
        ></iframe>
      </div>

      <h3 class="text-xl font-bold mb-2">{{ videoActual.titulo }}</h3>
      <p class="text-gray-600 mb-4">{{ videoActual.descripcion }}</p>

      <!-- Sistema de Likes -->
      <div class="flex items-center gap-2 mb-4">
        <button 
          @click="toggleLike"
          class="flex items-center gap-1"
          :class="{'text-blue-500': hasLike}"
        >
          <span>👍</span>
          {{ likes }}
        </button>
      </div>

      <!-- Sistema de Comentarios -->
      <div class="space-y-4">
        <h4 class="font-bold">Comentarios</h4>
        
        <form @submit.prevent="enviarComentario" class="mb-4">
          <textarea 
            v-model="nuevoComentario"
            class="w-full border rounded p-2 mb-2"
            rows="3"
            placeholder="Escribe un comentario..."
          ></textarea>
          <button 
            type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded"
            :disabled="isLoading"
          >
            Enviar Comentario
          </button>
        </form>

        <div class="space-y-4">
          <div 
            v-for="comentario in comentarios" 
            :key="comentario.id"
            class="border rounded p-4"
          >
            <div class="flex justify-between mb-2">
              <span class="font-semibold">{{ comentario.usuario.name }}</span>
              <span class="text-gray-500 text-sm">
                {{ new Date(comentario.created_at).toLocaleDateString() }}
              </span>
            </div>
            <p>{{ comentario.contenido }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const curso = ref(null);
const videos = ref([]);
const videoActual = ref(null);
const inscrito = ref(false);
const isLoading = ref(false);
const progresos = ref({});
const likes = ref(0);
const hasLike = ref(false);
const comentarios = ref([]);
const nuevoComentario = ref('');

// Cargar datos del curso
const cargarCurso = async () => {
  try {
    const response = await axios.get(`/api/v1/cursos/${route.params.id}`);
    curso.value = response.data;
    cargarVideos();
    verificarInscripcion();
  } catch (error) {
    console.error('Error al cargar el curso:', error);
  }
};

// Cargar videos del curso
const cargarVideos = async () => {
  if (!inscrito.value) return;
  
  try {
    const response = await axios.get(`/api/v1/cursos/${route.params.id}/videos`);
    videos.value = response.data;
    if (videos.value.length > 0 && !videoActual.value) {
      seleccionarVideo(videos.value[0]);
    }
  } catch (error) {
    console.error('Error al cargar videos:', error);
  }
};

// Verificar inscripción
const verificarInscripcion = async () => {
  try {
    const response = await axios.get(`/api/v1/cursos/${route.params.id}/inscripcion`);
    inscrito.value = response.data.inscrito;
  } catch (error) {
    console.error('Error al verificar inscripción:', error);
  }
};

// Inscribirse al curso
const inscribirseEnCurso = async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login');
    return;
  }

  try {
    isLoading.value = true;
    await axios.post(`/api/v1/cursos/${route.params.id}/inscribir`);
    inscrito.value = true;
    cargarVideos();
  } catch (error) {
    console.error('Error al inscribirse:', error);
  } finally {
    isLoading.value = false;
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
    isLoading.value = true;
    await axios.post(`/api/v1/videos/${videoActual.value.id}/comentar`, {
      contenido: nuevoComentario.value
    });
    nuevoComentario.value = '';
    cargarComentarios();
  } catch (error) {
    console.error('Error al enviar comentario:', error);
  } finally {
    isLoading.value = false;
  }
};

// Manejo de likes
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
    cargarLikes();
  } catch (error) {
    console.error('Error al dar like:', error);
  }
};

// Seguimiento de progreso
const iniciarSeguimientoVideo = () => {
  // Aquí implementarías la lógica para seguir el progreso del video
  // Podrías usar el API de YouTube Player para rastrear el tiempo de reproducción
};

const getProgresoClase = (videoId) => {
  const progreso = progresos.value[videoId] || 0;
  if (progreso === 100) return 'text-green-500';
  if (progreso > 0) return 'text-yellow-500';
  return 'text-gray-500';
};

const getProgresoTexto = (videoId) => {
  const progreso = progresos.value[videoId] || 0;
  return `${progreso}% completado`;
};

const getYoutubeEmbedUrl = (url) => {
  // Convertir URL de YouTube en URL de embed
  const videoId = url.split('v=')[1];
  return `https://www.youtube.com/embed/${videoId}`;
};

onMounted(() => {
  cargarCurso();
});
</script>