<template>
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-icon" :class="iconClass">
                <i :class="'bi bi-' + icon"></i>
            </div>
        </div>
        <h6 class="stat-label">{{ label }}</h6>
        <p class="stat-value">{{ value }}</p>
    </div>
</template>

<script>
    export default {
        props: ['icon', 'iconClass', 'label', 'value']
    }
</script>