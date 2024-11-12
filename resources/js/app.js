import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import { registerPlugins } from './plugins';

const app = createApp(App);
const pinia = createPinia();

app.use(router);
app.use(pinia);
registerPlugins(app);

app.mount('#app');