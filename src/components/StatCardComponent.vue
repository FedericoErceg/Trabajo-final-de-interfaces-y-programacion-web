<template>
    <!-- Tarjeta de estadística reutilizable, se usa 4 veces en el Dashboard -->
    <!-- Cada tarjeta muestra: un ícono, un label descriptivo y un valor numérico -->
    <div class="stat-card">
        <div class="stat-card-header">
            <!-- :class aplica la clase de color del ícono (ej: stat-icon-blue, stat-icon-green) -->
            <!-- Cada tarjeta tiene un color distinto, definido en dashboard.css -->
            <div class="stat-icon" :class="iconClass">
                <!-- :class construye dinámicamente la clase del ícono de Bootstrap Icons -->
                <!-- Ej: si icon="door-open" → genera class="bi bi-door-open" -->
                <i :class="'bi bi-' + icon"></i>
            </div>
        </div>
        <!-- label: texto descriptivo (ej: "Ocupadas hoy", "Reservas pendientes") -->
        <h6 class="stat-label">{{ label }}</h6>
        <!-- value: dato numérico o texto (ej: "3 / 15", "$45.000") -->
        <!-- Viene calculado desde el padre (DashboardView) que lo trae del backend -->
        <p class="stat-value">{{ value }}</p>
    </div>
</template>

<script>
    export default {
        // Todas las props vienen del padre (DashboardView):
        // icon: nombre del ícono de Bootstrap Icons sin el prefijo "bi-" (ej: "door-open")
        // iconClass: clase CSS para el color del fondo del ícono (ej: "stat-icon-blue")
        // label: texto descriptivo de la estadística
        // value: valor a mostrar, puede ser string o número
        props: ['icon', 'iconClass', 'label', 'value']
    }
</script>