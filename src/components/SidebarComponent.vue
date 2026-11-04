<template>
    <nav id="sidebar" class="sidebar" :class="{ collapsed: isCollapsed }">
        <div class="sidebar-header">
            <img src="/img/logo_hotel_web.png" alt="Logo" class="sidebar-logo">
            <span class="sidebar-title">Hotel Web</span>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-item" :class="{ active: currentPage === 'dashboard' }">
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
        <div class="sidebar-footer">
            <div class="sidebar-item">
                <a href="#" @click.prevent="cerrarSesion">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Cerrar sesión</span>
                </a>
            </div>
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
    props: ['currentPage'],
    data() {
        return {
            isCollapsed: false
        }
    },
    methods: {
        async cerrarSesion() {
            await signOut(auth)
            this.$router.push('/')
        }
    }
}
</script>