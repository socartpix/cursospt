// resources/js/views/admin/Comments.vue
<template>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Moderación de Comentarios</h1>

    <div class="mb-6 flex gap-4">
      <button 
        v-for="estado in estados" 
        :key="estado.value"
        @click="filtroEstado = estado.value"
        class="px-4 py-2 rounded"
        :class="{
          'bg-blue-500 text-white': filtroEstado === estado.value,
          'bg-gray-200': filtroEstado !== estado.value
        }"
      >
        {{ estado.label }}
      </button>
    </div>

    <div class="space-y-4">
      <div 
        v-for="comentario in comentariosFiltrados" 
        :key="comentario.id"
        class="bg-white rounded-lg shadow p-6"
      >
        <div class="flex justify-between items-start mb-4">
          <div>
            <p class="font-bold">{{ comentario.usuario.name }}</p>
            <p class="text-sm text-gray-500">
              {{ new Date(comentario.created_at).toLocaleDateString() }}
            </p>
          </div>
          <div class="flex gap-2">
            <button 
              v-if="!comentario.aprobado"
              @click="aprobarComentario(comentario.id)"
              class="text-green-500 hover:underline"
            >
              Aprobar
            </button>
            <button 
              @click="rechazarComentario(comentario.id)"
              class="text-red-500 hover:underline"
            >
              Rechazar
            </button>
          </div>
        </div>

        <div class="mb-4">
          <p class="text-gray-600">{{ comentario.contenido }}</p>
        </div>

        <div class="text-sm text-gray-500">
          <p>Video: {{ comentario.video.titulo }}</p>
          <p>Curso: {{ comentario.video.curso.titulo }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const comentarios = ref([]);
const filtroEstado = ref('pendiente');

const estados = [
  { value: 'pendiente', label: 'Pendientes' },
  { value: 'aprobado', label: 'Aprobados' },
  { value: 'rechazado', label: 'Rechazados' }
];

const comentariosFiltrados = computed(() => {
  return comentarios.value.filter(c => {
    if (filtroEstado.value === 'pendiente') return !c.aprobado && !c.rechazado;
    if (filtroEstado.value === 'aprobado') return c.aprobado;
    if (filtroEstado.value === 'rechazado') return c.rechazado;
    return true;
  });
});

const cargarComentarios = async () => {
  try {
    const response = await axios.get('/api/v1/admin/comentarios');
    comentarios.value = response.data;
  } catch (error) {
    console.error('Error al cargar comentarios:', error);
  }
};

const aprobarComentario = async (id) => {
  try {
    await axios.put(`/api/v1/admin/comentarios/${id}/aprobar`);
    await cargarComentarios();
  } catch (error) {
    console.error('Error al aprobar comentario:', error);
  }
};

const rechazarComentario = async (id) => {
  try {
    await axios.put(`/api/v1/admin/comentarios/${id}/rechazar`);
    await cargarComentarios();
  } catch (error) {
    console.error('Error al rechazar comentario:', error);
  }
};

onMounted(() => {
  cargarComentarios();
});
</script>