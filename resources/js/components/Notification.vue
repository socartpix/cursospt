// resources/js/components/Notification.vue
<template>
  <TransitionGroup
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
    class="fixed z-50 top-4 right-4 space-y-4"
  >
    <div
      v-for="notification in notifications"
      :key="notification.id"
      class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <!-- Icono según el tipo -->
            <div :class="[
              'w-6 h-6 flex items-center justify-center rounded-full',
              {
                'bg-green-100': notification.type === 'success',
                'bg-red-100': notification.type === 'error',
                'bg-blue-100': notification.type === 'info',
                'bg-yellow-100': notification.type === 'warning'
              }
            ]">
              <span :class="[
                'text-lg',
                {
                  'text-green-500': notification.type === 'success',
                  'text-red-500': notification.type === 'error',
                  'text-blue-500': notification.type === 'info',
                  'text-yellow-500': notification.type === 'warning'
                }
              ]">
                {{ getIcon(notification.type) }}
              </span>
            </div>
          </div>
          <div class="ml-3 w-0 flex-1">
            <p class="text-sm font-medium text-gray-900">
              {{ notification.title }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              {{ notification.message }}
            </p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button
              @click="removeNotification(notification.id)"
              class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
            >
              <span class="sr-only">Cerrar</span>
              <span class="text-lg">×</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </TransitionGroup>
</template>

<script setup>
import { ref } from 'vue';
import { TransitionGroup } from 'vue';

const notifications = ref([]);
let nextId = 0;

const getIcon = (type) => {
  switch (type) {
    case 'success': return '✓';
    case 'error': return '✕';
    case 'info': return 'ℹ';
    case 'warning': return '⚠';
    default: return '•';
  }
};

const addNotification = (notification) => {
  const id = nextId++;
  notifications.value.push({
    id,
    ...notification,
    timeout: setTimeout(() => {
      removeNotification(id);
    }, notification.duration || 5000)
  });
};

const removeNotification = (id) => {
  const index = notifications.value.findIndex(n => n.id === id);
  if (index > -1) {
    clearTimeout(notifications.value[index].timeout);
    notifications.value.splice(index, 1);
  }
};

// Exponer métodos para usar globalmente
defineExpose({
  addNotification
});
</script>