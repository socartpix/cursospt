<template>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Estudiantes</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <!-- Filtros -->
      <div class="p-4 border-b">
        <div class="flex gap-4">
          <input 
            v-model="busqueda"
            type="text"
            placeholder="Buscar estudiante..."
            class="border rounded p-2 flex-grow"
            @input="filtrarEstudiantes"
          >
          <select 
            v-model="cursoFiltrado"
            class="border rounded p-2"
            @change="filtrarEstudiantes"
          >
            <option value="">Todos los cursos</option>
            <option v-for="curso in cursos" :key="curso.id" :value="curso.id">
              {{ curso.titulo }}
            </option>
          </select>
        </div>
      </div>

      <!-- Tabla de Estudiantes -->
      <table class="min-w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cursos Inscritos</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progreso</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="usuario in usuariosFiltrados" :key="usuario.id">
            <td class="px-6 py-4">{{ usuario.name }}</td>
            <td class="px-6 py-4">{{ usuario.email }}</td>
            <td class="px-6 py-4">{{ usuario.cursos_count }}</td>
            <td class="px-6 py-4">
              {{ calcularProgresoPromedio(usuario) }}%
            </td>
            <td class="px-6 py-4">
              <button 
                @click="verDetalles(usuario)"
                class="text-blue-500 hover:underline"
              >
                Ver Detalles
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal de Detalles -->
    <div v-if="showDetalles" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-3xl">
        <h2 class="text-2xl font-bold mb-4">Detalles del Estudiante</h2>
        
        <div class="mb-4">
          <p><strong>Nombre:</strong> {{ usuarioSeleccionado?.name }}</p>
          <p><strong>Email:</strong> {{ usuarioSeleccionado?.email }}</p>
        </div>

        <h3 class="text-xl font-bold mb-2">Cursos Inscritos</h3>
        <div class="space-y-4">
          <div v-for="curso in cursosUsuario" :key="curso.id" class="border p-4 rounded">
            <h4 class="font-bold">{{ curso.titulo }}</h4>
            <p>Progreso: {{ curso.pivot.progreso }}%</p>
            <div class="mt-2">
              <h5 class="font-semibold mb-1">Videos Vistos:</h5>
              <ul class="space-y-1">
                <li v-for="video in curso.videos" :key="video.id" class="flex items-center">
                  <span class="w-4 h-4 mr-2 rounded-full" :class="{
                    'bg-green-500': getProgresoVideo(video.id) === 100,
                    'bg-yellow-500': getProgresoVideo(video.id) > 0 && getProgresoVideo(video.id) < 100,
                    'bg-gray-300': getProgresoVideo(video.id) === 0
                  }"></span>
                  {{ video.titulo }} - {{ getProgresoVideo(video.id) }}%
                </li>
              </ul>
            </div>
          </div>
        </div>

        <button 
          @click="showDetalles = false"
          class="mt-4 px-4 py-2 border rounded"
        >
          Cerrar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const usuarios = ref([]);
const cursos = ref([]);
const busqueda = ref('');
const cursoFiltrado = ref('');
const showDetalles = ref(false);
const usuarioSeleccionado = ref(null);
const cursosUsuario = ref([]);

const usuariosFiltrados = computed(() => {
  let filtrados = [...usuarios.value];
  
  if (busqueda.value) {
    filtrados = filtrados.filter(e => 
      e.name.toLowerCase().includes(busqueda.value.toLowerCase()) ||
      e.email.toLowerCase().includes(busqueda.value.toLowerCase())
    );
  }
  
  if (cursoFiltrado.value) {
    filtrados = filtrados.filter(e => 
      e.cursos.some(c => c.id === parseInt(cursoFiltrado.value))
    );
  }
  
  return filtrados;
});

const cargarUsuarios = async () => {
  console.log("usuarios"); 
  const response = await axios.get('/api/v1/users');
  console.log("USERS")
  console.log(usuarios.value )
  usuarios.value = response.data;
};

const cargarCursos = async () => {

    const response = await axios.get('/api/v1/cursos');
    cursos.value = response.data.data;
    console.log("cursos")
    console.log(cursos.value)
  
};

const verDetalles = async (usuario) => {
  usuarioSeleccionado.value = usuario;
  const response = await axios.get(`/api/v1/users/${usuario.id}/cursos`);
  cursosUsuario.value = response.data;
  showDetalles.value = true;
};

const calcularProgresoPromedio = (usuario) => {
  if (!usuario.cursos?.length) return 0;
  const total = usuario.cursos.reduce((sum, curso) => sum + curso.pivot.progreso, 0);
  return Math.round(total / usuario.cursos.length);
};

const getProgresoVideo = (videoId) => {
  // Implementar lógica para obtener el progreso de un video específico
  return 0;
};

const filtrarEstudiantes = () => {
  // La filtración se maneja automáticamente por el computed property
};

onMounted(() => {
  console.log("cargando usuarios")
  cargarUsuarios();
  cargarCursos();
});
</script>