<template>
    <header>
        <!-- Navbar horizontal de Bootstrap, se usa solo en la vista de Calendario -->
        <!-- En las demás vistas se usa el SidebarComponent, pero el calendario necesita todo el ancho -->
        <!-- porque la grilla de días (hasta 31 columnas) no deja espacio para el sidebar lateral -->
        <nav class="navbar navbar-expand-lg" style="background-color: #ffffff; border-bottom: 1px solid #e5e5e5;">
            <div class="container-fluid">
                <!-- router-link en vez de <a href>: navega sin recargar la página (SPA) -->
                <!-- Lleva al dashboard al hacer clic en el logo -->
                <router-link class="navbar-brand d-flex align-items-center gap-2" to="/dashboard">
                    <!-- Logo cargado desde la carpeta public/img/ -->
                    <img src="/img/logo_hotel_web.png" alt="Logo" style="max-width: 80px;">
                    <span style="font-weight: 600;">Hotel Web</span>
                </router-link>
                <!-- Botón hamburguesa: aparece en pantallas chicas (responsive) -->
                <!-- data-bs-toggle y data-bs-target son atributos de Bootstrap para colapsar el menú -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCalendario">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Menú colapsable: se oculta en mobile y se muestra al tocar el hamburguesa -->
                <div class="collapse navbar-collapse" id="navbarCalendario">
                    <!-- ms-auto empuja los botones a la derecha, mt-2 mt-lg-0 agrega margen arriba solo en mobile -->
                    <div class="d-flex gap-2 ms-auto mt-2 mt-lg-0">
                        <!-- router-link genera un <a> que navega con Vue Router sin recargar -->
                        <!-- Cada botón lleva a una sección diferente del sistema -->
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
        // Este componente no tiene props, data, ni methods porque es solo visual.
        name: 'NavbarComponent'
    }
</script>