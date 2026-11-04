<template>
    <header>
        <nav class="navbar navbar-expand-lg" style="background-color: #ffffff; border-bottom: 1px solid #e5e5e5;">
            <div class="container-fluid">
                <router-link class="navbar-brand d-flex align-items-center gap-2" to="/dashboard">
                    <img src="/img/logo_hotel_web.png" alt="Logo" style="max-width: 80px;">
                    <span style="font-weight: 600;">Hotel Web</span>
                </router-link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCalendario">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCalendario">
                    <div class="d-flex gap-2 ms-auto mt-2 mt-lg-0">
                        <router-link to="/dashboard" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </router-link>
                        <router-link to="/reservas" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-calendar-check me-1"></i>Reservas
                        </router-link>
                        <router-link to="/habitaciones" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-door-open me-1"></i>Habitaciones
                        </router-link>
                        <router-link to="/categorias" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-tags me-1"></i>Categorías
                        </router-link>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
    export default {
        name: 'NavbarComponent'
    }
</script>