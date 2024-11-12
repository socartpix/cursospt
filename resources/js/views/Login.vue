// resources/js/views/Login.vue
<template>
  <div class="max-w-md mx-auto mt-10 px-4">
    <h1 class="text-3xl font-bold mb-6">Iniciar Sesión</h1>
    
    <form @submit.prevent="handleSubmit" class="bg-white p-6 rounded-lg shadow">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input 
            type="email" 
            v-model="email" 
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Contraseña</label>
          <input 
            type="password" 
            v-model="password" 
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          >
        </div>

        <button 
          type="submit"
          class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
        >
          Iniciar Sesión
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const email = ref('');
const password = ref('');

const handleSubmit = async () => {
  try {
    await authStore.login({
      email: email.value,
      password: password.value
    });
    router.push('/');
  } catch (error) {
    console.error('Error de login:', error);
  }
};
</script>