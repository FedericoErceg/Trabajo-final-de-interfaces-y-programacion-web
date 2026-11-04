import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: () => import('../views/LoginView.vue') },
        { path: '/dashboard', component: () => import('../views/DashboardView.vue') },
        { path: '/habitaciones', component: () => import('../views/HabitacionesView.vue') },
        { path: '/categorias', component: () => import('../views/CategoriasView.vue') },
        { path: '/reservas', component: () => import('../views/ReservasView.vue') },
        { path: '/calendario', component: () => import('../views/CalendarioView.vue') }
    ]
})

export default router