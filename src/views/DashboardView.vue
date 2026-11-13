<template>
    <!-- Layout con flexbox: sidebar a la izquierda, contenido a la derecha -->
    <div class="d-flex">
        <!-- Sidebar reutilizable, currentPage="dashboard" marca el link activo -->
        <SidebarComponent currentPage="dashboard" />

        <main class="main-content">
            <!-- Encabezado de la página -->
            <div class="content-header">
                <div>
                    <h1>Dashboard</h1>
                    <p class="text-muted mb-0">Bienvenido al panel de administración</p>
                </div>
            </div>

            <!-- Fila de 4 tarjetas de estadísticas -->
            <!-- Cada StatCardComponent recibe props: icon, iconClass, label y value -->
            <!-- :value usa v-bind para pasar valores dinámicos desde el estado -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <!-- Muestra "ocupadas / total" concatenando los valores del backend -->
                    <StatCardComponent icon="door-open" iconClass="stat-icon-blue" label="Ocupadas hoy" :value="stats.ocupadas + ' / ' + stats.totalHabitaciones" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="calendar-check" iconClass="stat-icon-green" label="Reservas pendientes" :value="stats.pendientes" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <StatCardComponent icon="people" iconClass="stat-icon-purple" label="Check-ins hoy" :value="stats.checkins" />
                </div>
                <div class="col-md-6 col-lg-3">
                    <!-- toLocaleString('es-AR') formatea el número con separador de miles argentino -->
                    <StatCardComponent icon="cash-stack" iconClass="stat-icon-gold" label="Ingresos del mes" :value="'$' + Number(stats.ingresos).toLocaleString('es-AR')" />
                </div>
            </div>

            <!-- Fila inferior: tabla de últimas reservas y barras de disponibilidad -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Recibe el array de últimas 5 reservas por prop -->
                    <UltimasReservasComponent :reservas="stats.ultimasReservas" />
                </div>
                <div class="col-lg-4">
                    <!-- disponibilidadConColor es un computed que agrega un color a cada categoría -->
                    <DisponibilidadCategoriaComponent :categorias="disponibilidadConColor" />
                </div>
            </div>
        </main>
    </div>
</template>

<script>

    // Importación de los 4 componentes que usa el dashboard
    import SidebarComponent from '@/components/SidebarComponent.vue'
    import StatCardComponent from '@/components/StatCardComponent.vue'
    import UltimasReservasComponent from '@/components/UltimasReservasComponent.vue'
    import DisponibilidadCategoriaComponent from '@/components/DisponibilidadCategoriaComponent.vue'

    export default {
        // Registro de componentes para usarlos en el template
        components: {
            SidebarComponent,
            StatCardComponent,
            UltimasReservasComponent,
            DisponibilidadCategoriaComponent
        },

        // Estado reactivo: se inicializa en 0 y se llena con el fetch en mounted()
        data() {
            return {
                stats: {
                    ocupadas: 0,   // Habitaciones ocupadas hoy
                    totalHabitaciones: 0,  // Total de habitaciones disponibles
                    pendientes: 0,   // Reservas en estado "pendiente"
                    checkins: 0,   // Reservas que inician hoy
                    ingresos: 0,   // Suma de totales de reservas del mes
                    ultimasReservas: [], // Últimas 5 reservas para la tabla
                    disponibilidad: []  // Ocupación por categoría para las barras
                }
            }
        },

        // Propiedades calculadas: se recalculan cuando cambian sus dependencias
        computed: {
            // Agrega un color distinto a cada categoría para las barras de progreso
            // map() recorre el array y spread (...cat) copia las propiedades originales
            // El operador % (módulo) hace que los colores se repitan si hay más categorías que colores
            disponibilidadConColor() {
                const colores = ['#0d6efd', '#8b7355', '#198754', '#9b59b6', '#e74c3c', '#f39c12', '#1abc9c', '#e67e22', '#3498db']
                return this.stats.disponibilidad.map((cat, i) => ({
                    ...cat,
                    color: colores[i % colores.length]
                }))
            }
        },

        // mounted() se ejecuta al cargar la página
        // Hace un único fetch al backend que devuelve todos los datos del dashboard en un solo JSON
        async mounted() {
            const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/dashboard/dashboard_back.php')
            const data = await res.json()       // Parsea la respuesta JSON
            this.stats = data                   // Actualiza el estado, lo que re-renderiza todos los componentes
        }
    }
</script>