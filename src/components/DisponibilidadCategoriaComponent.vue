<template>
    <div class="content-card">
        <h5 class="mb-4">Disponibilidad por categoría</h5>
        <div v-for="cat in categorias" :key="cat.tipo" class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span class="stat-label">{{ cat.tipo }}</span>
                <span class="stat-label fw-medium">{{ cat.ocupadas }} / {{ cat.total }}</span>
            </div>
            <div class="progress" style="height: 8px;">
                <div class="progress-bar" :style="{ width: (cat.ocupadas / cat.total * 100) + '%', backgroundColor: cat.color }"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['categorias']
    }
</script>