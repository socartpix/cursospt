// resources/js/components/Loading.vue
<template>
  <div 
    v-if="isLoading"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
  >
    <div class="bg-white p-6 rounded-lg shadow-xl">
      <div class="flex items-center space-x-4">
        <!-- Spinner animado -->
        <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
        <span class="text-lg">{{ message || 'Cargando...' }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const isLoading = ref(false);
const message = ref('');

const show = (msg = '') => {
  message.value = msg;
  isLoading.value = true;
};

const hide = () => {
  isLoading.value = false;
  message.value = '';
};

// Exponer métodos para usar globalmente
defineExpose({
  show,
  hide
});
</script>