<template>
    <div class="d-flex">
        <SidebarComponent currentPage="dashboard" />

        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1>Dashboard</h1>
                    <p class="text-muted mb-0">Bienvenido al panel de administración</p>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="door-open" iconClass="stat-icon-blue" label="Ocupadas hoy" value="-- / --" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="calendar-check" iconClass="stat-icon-green" label="Reservas pendientes" value="--" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="people" iconClass="stat-icon-purple" label="Check-ins hoy" value="--" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="cash-stack" iconClass="stat-icon-gold" label="Ingresos del mes" value="--" />
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <UltimasReservasComponent :reservas="[]" />
                </div>
                <div class="col-lg-4">
                    <DisponibilidadCategoriaComponent :categorias="[]" />
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import SidebarComponent from '@/components/SidebarComponent.vue'
    import StatCardComponent from '@/components/StatCardComponent.vue'
    import UltimasReservasComponent from '@/components/UltimasReservasComponent.vue'
    import DisponibilidadCategoriaComponent from '@/components/DisponibilidadCategoriaComponent.vue'

    export default {
        components: {
            SidebarComponent,
            StatCardComponent,
            UltimasReservasComponent,
            DisponibilidadCategoriaComponent
        }
    }
</script>