// resources/js/App.vue
<template>
  <div>
 <nav class="bg-gray-800 text-white p-4">
      <div class="container mx-auto flex justify-between items-center">
        <router-link to="/" class="text-xl font-bold">Plataforma de Cursos</router-link>
        
        <div v-if="authStore.isAuthenticated">
          <!-- Menú de Administrador -->
          <div v-if="authStore.isAdmin" class="flex items-center space-x-4">
            <router-link to="/admin/cursos" class="hover:text-gray-300">Gestionar Cursos</router-link>
            <router-link to="/admin/estudiantes" class="hover:text-gray-300">Gestionar Estudiantes</router-link> 
           
            <!-- <router-link to="/admin/videos" class="hover:text-gray-300">Gestionar Videos</router-link> -->
            <!-- <router-link to="/admin/categorias" class="hover:text-gray-300">Categorías</router-link>
            <span>| /admin/estudiantes</span> -->
          </div>
          
          <span class="mr-4">{{ authStore.user?.name }}</span>
          <button @click="logout" class="text-white">Cerrar Sesión</button>
        </div>
        <div v-else>
          <router-link to="/login" class="mr-4">Iniciar Sesión</router-link>
          <router-link to="/register">Registrarse</router-link>
        </div>
      </div>
    </nav>


    <main class="container mx-auto p-4">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const logout = async () => {
  try {
    await authStore.logout();
    router.push('/login');
  } catch (error) {
    console.error('Error al cerrar sesión:', error);
  }
};

onMounted(async () => {
  if (authStore.token) {
    try {
      await authStore.fetchUser();
    } catch (error) {
      router.push('/login');
    }
  }
});
</script>