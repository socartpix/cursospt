// resources/js/views/Register.vue
<template>
  <div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Registrarse</h2>
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label class="block mb-1">Nombre</label>
        <input 
          type="text" 
          v-model="name" 
          required
          class="w-full border rounded p-2"
        >
      </div>

      <div>
        <label class="block mb-1">Email</label>
        <input 
          type="email" 
          v-model="email" 
          required
          class="w-full border rounded p-2"
        >
      </div>
      
      <div>
        <label class="block mb-1">Contraseña</label>
        <input 
          type="password" 
          v-model="password" 
          required
          class="w-full border rounded p-2"
        >
      </div>

      <div>
        <label class="block mb-1">Confirmar Contraseña</label>
        <input 
          type="password" 
          v-model="password_confirmation" 
          required
          class="w-full border rounded p-2"
        >
      </div>

      <div class="text-red-500" v-if="error">
        {{ error }}
      </div>

      <button 
        type="submit" 
        class="w-full bg-blue-500 text-white rounded p-2"
        :disabled="isLoading"
      >
        {{ isLoading ? 'Cargando...' : 'Registrarse' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const error = ref('');
const isLoading = ref(false);

const handleSubmit = async () => {
  try {
    isLoading.value = true;
    await authStore.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    });
    router.push('/');
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al registrarse';
  } finally {
    isLoading.value = false;
  }
};
</script>