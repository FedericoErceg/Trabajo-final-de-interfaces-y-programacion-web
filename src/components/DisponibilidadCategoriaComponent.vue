<template>
    <!-- Tarjeta que muestra barras de progreso de ocupación por categoría de habitación -->
    <div class="content-card">
        <h5 class="mb-4">Disponibilidad por categoría</h5>
        <!-- v-for recorre el array de categorías recibido por prop -->
        <!-- :key identifica cada elemento de forma única para que Vue optimice el renderizado -->
        <div v-for="cat in categorias" :key="cat.tipo" class="mb-3">
            <!-- Fila con el nombre de la categoría a la izquierda y "ocupadas / total" a la derecha -->
            <div class="d-flex justify-content-between mb-1">
                <span class="stat-label">{{ cat.tipo }}</span>
                <span class="stat-label fw-medium">{{ cat.ocupadas }} / {{ cat.total }}</span>
            </div>
            <!-- Barra de progreso de Bootstrap -->
            <div class="progress" style="height: 8px;">
                <!-- :style aplica estilos dinámicos -->
                <!-- width se calcula como porcentaje: (ocupadas / total) * 100 -->
                <!-- backgroundColor viene del computed disponibilidadConColor del padre (DashboardView) -->
                <div class="progress-bar" :style="{ width: (cat.ocupadas / cat.total * 100) + '%', backgroundColor: cat.color }"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        // props: datos que recibe del componente padre (DashboardView)
        // categorias es un array de objetos con: tipo, ocupadas, total y color
        props: ['categorias']
    }
</script>