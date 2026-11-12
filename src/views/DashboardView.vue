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
                    <StatCardComponent icon="door-open" iconClass="stat-icon-blue" label="Ocupadas hoy" :value="stats.ocupadas + ' / ' + stats.totalHabitaciones" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="calendar-check" iconClass="stat-icon-green" label="Reservas pendientes" :value="stats.pendientes" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="people" iconClass="stat-icon-purple" label="Check-ins hoy" :value="stats.checkins" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="cash-stack" iconClass="stat-icon-gold" label="Ingresos del mes" :value="'$' + Number(stats.ingresos).toLocaleString('es-AR')" />
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <UltimasReservasComponent :reservas="stats.ultimasReservas" />
                </div>
                <div class="col-lg-4">
                    <DisponibilidadCategoriaComponent :categorias="disponibilidadConColor" />
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
        },
        data() {
            return {
                stats: {
                    ocupadas: 0,
                    totalHabitaciones: 0,
                    pendientes: 0,
                    checkins: 0,
                    ingresos: 0,
                    ultimasReservas: [],
                    disponibilidad: []
                }
            }
        },
        computed: {
            disponibilidadConColor() {
                const colores = ['#0d6efd', '#8b7355', '#198754', '#9b59b6', '#e74c3c', '#f39c12', '#1abc9c', '#e67e22', '#3498db']
                return this.stats.disponibilidad.map((cat, i) => ({
                    ...cat,
                    color: colores[i % colores.length]
                }))
            }
        },
        async mounted() {
            const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/dashboard/dashboard_back.php')
            const data = await res.json()
            this.stats = data
        }
    }
</script>