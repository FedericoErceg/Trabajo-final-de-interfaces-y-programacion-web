<template>
    <!-- Sidebar lateral de navegación, se usa en todas las páginas excepto Calendario y Login -->
    <!-- :class="{ collapsed: isCollapsed }" agrega la clase 'collapsed' cuando isCollapsed es true -->
    <!-- Eso achica el sidebar mostrando solo los íconos (el CSS de dashboard.css maneja la animación) -->
    <nav id="sidebar" class="sidebar" :class="{ collapsed: isCollapsed }">
        <!-- Header del sidebar con logo y nombre del sistema -->
        <div class="sidebar-header">
            <img src="/img/logo_hotel_web.png" alt="Logo" class="sidebar-logo">
            <span class="sidebar-title">Hotel Web</span>
        </div>
        <!-- Lista de links de navegación -->
        <ul class="sidebar-nav">
            <!-- :class="{ active: currentPage === 'dashboard' }" resalta el link de la página actual -->
            <!-- currentPage viene por prop desde cada vista (ej: currentPage="habitaciones") -->
            <li class="sidebar-item" :class="{ active: currentPage === 'dashboard' }">
                <!-- router-link genera un <a> que navega con Vue Router sin recargar la página -->
                <router-link to="/dashboard">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </router-link>
            </li>
            <li class="sidebar-item" :class="{ active: currentPage === 'reservas' }">
                <router-link to="/reservas">
                    <i class="bi bi-calendar-check"></i>
                    <span>Reservas</span>
                </router-link>
            </li>
            <li class="sidebar-item" :class="{ active: currentPage === 'habitaciones' }">
                <router-link to="/habitaciones">
                    <i class="bi bi-door-open"></i>
                    <span>Habitaciones</span>
                </router-link>
            </li>
            <li class="sidebar-item" :class="{ active: currentPage === 'categorias' }">
                <router-link to="/categorias">
                    <i class="bi bi-tags"></i>
                    <span>Categorías</span>
                </router-link>
            </li>
            <li class="sidebar-item" :class="{ active: currentPage === 'calendario' }">
                <router-link to="/calendario">
                    <i class="bi bi-calendar3"></i>
                    <span>Calendario</span>
                </router-link>
            </li>
        </ul>
        <!-- Footer del sidebar con cerrar sesión y botón de colapsar -->
        <div class="sidebar-footer">
            <div class="sidebar-item">
                <!-- @click.prevent evita el comportamiento por defecto del <a> (no navega a #) -->
                <!-- y ejecuta el método cerrarSesion() -->
                <a href="#" @click.prevent="cerrarSesion">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Cerrar sesión</span>
                </a>
            </div>
            <!-- Botón que alterna el estado colapsado del sidebar -->
            <!-- @click invierte el valor de isCollapsed (true ↔ false) -->
            <!-- El ícono cambia dinámicamente: flechas a la derecha si está colapsado, a la izquierda si está expandido -->
            <button class="sidebar-toggle" @click="isCollapsed = !isCollapsed">
                <i :class="isCollapsed ? 'bi bi-chevron-double-right' : 'bi bi-chevron-double-left'"></i>
            </button>
        </div>
    </nav>
</template>

<script>
    import { signOut } from 'firebase/auth'
    import { auth } from '../firebase'

    export default {
        // currentPage: string que indica qué página está activa (ej: 'dashboard', 'habitaciones')
        // Se usa para resaltar el link correspondiente en el sidebar
        props: ['currentPage'],
        // Estado reactivo local del componente
        data() {
            return {
                isCollapsed: false      // Controla si el sidebar está colapsado (solo íconos) o expandido
            }
        },
        methods: {
            // Cierra la sesión en Firebase y redirige al login
            async cerrarSesion() {
                await signOut(auth)         // Cierra la sesión de Firebase Authentication
                this.$router.push('/')      // Redirige a la ruta '/' (LoginView) usando Vue Router
            }
        }
    }
</script>