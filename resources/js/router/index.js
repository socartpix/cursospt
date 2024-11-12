// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

// Vistas públicas
import Home from '@/views/Home.vue';
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue';
import ListaCursos from '@/views/ListaCursos.vue';
import DetalleCurso from '@/views/DetalleCurso.vue';

// Vistas de administración
import Panel from '@/views/admin/Panel.vue';
import Cursos from '@/views/admin/Cursos.vue';
import Estudiantes from '@/views/admin/Estudiantes.vue';
import Comentarios from '@/views/admin/Comentarios.vue';


const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/registro',
        name: 'registro',
        component: Register
    },
    {
        path: '/cursos',
        name: 'cursos',
        component: ListaCursos
    },
    {
        path: '/cursos/:id',
        name: 'detalle-curso',
        component: DetalleCurso,
        props: true
    },
    // Panel de administración
    {
        path: '/admin',
        name: 'admin',
        component: Panel,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/cursos',
        name: 'admin.cursos',
        component: Cursos,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/estudiantes',
        name: 'admin.estudiantes',
        component: Estudiantes,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/comentarios',
        name: 'admin.comentarios',
        component: Comentarios,
        meta: { requiresAuth: true, requiresAdmin: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navegación guard para proteger rutas
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // Verificar si la ruta requiere autenticación
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/login');
        return;
    }

    // Verificar si la ruta requiere rol de administrador
    if (to.meta.requiresAdmin && !authStore.isAdmin) {
        next('/');
        return;
    }

    next();
});

export default router;