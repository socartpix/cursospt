// resources/js/components/Navbar.vue
<template>
  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between h-16">
        <!-- Logo y navegación principal -->
        <div class="flex">
          <router-link to="/" class="flex items-center">
            <span class="text-xl font-bold text-blue-600">CursosOnline</span>
          </router-link>

          <div class="hidden md:flex ml-10 space-x-4">
            <router-link 
              to="/cursos" 
              class="px-3 py-2 rounded-md hover:bg-gray-100"
              active-class="text-blue-600"
            >
              Explorar Cursos
            </router-link>
            
            <router-link 
              v-if="authStore.isAuthenticated"
              to="/my-cursos" 
              class="px-3 py-2 rounded-md hover:bg-gray-100"
              active-class="text-blue-600"
            >
              Mis Cursos
            </router-link>

            <router-link 
              v-if="authStore.isAdmin"
              to="/admin" 
              class="px-3 py-2 rounded-md hover:bg-gray-100"
              active-class="text-blue-600"
            >
              Administración
            </router-link>
          </div>
        </div>

        <!-- Botones de usuario -->
        <div class="flex items-center">
          <template v-if="authStore.isAuthenticated">
            <div class="relative" v-click-away="cerrarMenu">
              <button 
                @click="toggleMenu"
                class="flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-gray-100"
              >
                <span>{{ authStore.user?.name }}</span>
                <span class="text-xs">▼</span>
              </button>

              <!-- Menú desplegable -->
              <div 
                v-if="menuAbierto"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1"
              >
                <router-link
                  to="/profile"
                  class="block px-4 py-2 hover:bg-gray-100"
                >
                  Mi Perfil
                </router-link>
                <button
                  @click="logout"
                  class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                >
                  Cerrar Sesión
                </button>
              </div>
            </div>
          </template>
          <template v-else>
            <router-link 
              to="/login"
              class="px-4 py-2 hover:text-blue-600"
            >
              Iniciar Sesión
            </router-link>
            <router-link 
              to="/register"
              class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
            >
              Registrarse
            </router-link>
          </template>
        </div>

        <!-- Menú móvil -->
        <div class="flex items-center md:hidden">
          <button 
            @click="menuMovilAbierto = !menuMovilAbierto"
            class="p-2 rounded-md hover:bg-gray-100"
          >
            <span class="text-xl">☰</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Panel móvil -->
    <div 
      v-if="menuMovilAbierto"
      class="md:hidden"
    >
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link 
          to="/cursos"
          class="block px-3 py-2 rounded-md hover:bg-gray-100"
          active-class="text-blue-600"
        >
          Explorar Cursos
        </router-link>
        
        <router-link 
          v-if="authStore.isAuthenticated"
          to="/my-cursos"
          class="block px-3 py-2 rounded-md hover:bg-gray-100"
          active-class="text-blue-600"
        >
          Mis Cursos
        </router-link>

        <router-link 
          v-if="authStore.isAdmin"
          to="/admin"
          class="block px-3 py-2 rounded-md hover:bg-gray-100"
          active-class="text-blue-600"
        >
          Administración
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const menuAbierto = ref(false);
const menuMovilAbierto = ref(false);

const toggleMenu = () => {
  menuAbierto.value = !menuAbierto.value;
};

const cerrarMenu = () => {
  menuAbierto.value = false;
};

const logout = async () => {
  try {
    await authStore.logout();
    router.push('/login');
  } catch (error) {
    console.error('Error al cerrar sesión:', error);
  }
};
</script>