// resources/js/plugins/index.js
import Notification from '../components/Notification.vue';
import Loading from '../components/Loading.vue';

export function registerPlugins(app) {
  // Crear instancias globales
  const notification = createNotification();
  const loading = createLoading();

  // Registrar componentes globales
  app.component('Notification', Notification);
  app.component('Loading', Loading);

  // Agregar a las propiedades globales
  app.config.globalProperties.$notification = notification;
  app.config.globalProperties.$loading = loading;
}

function createNotification() {
  let notificationInstance = null;

  return {
    setInstance: (instance) => {
      notificationInstance = instance;
    },
    addNotification: (options) => {
      if (notificationInstance) {
        notificationInstance.addNotification(options);
      }
    }
  };
}

function createLoading() {
  let loadingInstance = null;

  return {
    setInstance: (instance) => {
      loadingInstance = instance;
    },
    show: (message) => {
      if (loadingInstance) {
        loadingInstance.show(message);
      }
    },
    hide: () => {
      if (loadingInstance) {
        loadingInstance.hide();
      }
    }
  };
}