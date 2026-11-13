<template>
    <!-- Tabla de últimas reservas que se muestra en el Dashboard -->
    <!-- A diferencia de TablaReservasComponent, esta es solo de lectura (sin botones de editar/eliminar) -->
    <!-- Muestra las últimas 5 reservas ordenadas por fecha de creación (LIMIT 5 en el backend) -->
    <div class="content-card">
        <div class="content-card-header">
            <h5>Últimas reservas</h5>
            <!-- router-link lleva a la página completa de reservas para ver todas -->
            <router-link to="/reservas" class="btn-ver-todo">Ver todas</router-link>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Pasajero</th>
                        <th>Habitación</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Mensaje cuando no hay reservas todavía -->
                    <tr v-if="reservas.length === 0">
                        <td colspan="5" class="text-center text-muted py-4">No hay reservas aún</td>
                    </tr>
                    <!-- v-for genera una fila por cada reserva del array -->
                    <tr v-for="reserva in reservas" :key="reserva.id_reserva">
                        <!-- Concatena nombre y apellido en una celda -->
                        <td>{{ reserva.nombre }} {{ reserva.apellido }}</td>
                        <!-- numero viene del JOIN con la tabla habitaciones en el backend -->
                        <td>{{ reserva.numero }}</td>
                        <td>{{ reserva.fecha_entrada }}</td>
                        <td>{{ reserva.fecha_salida }}</td>
                        <td>
                            <!-- Badges de colores según el estado de la reserva -->
                            <!-- v-if / v-else-if / v-else evalúa en orden y renderiza solo el que coincide -->
                            <span v-if="reserva.estado === 'confirmada'" class="badge bg-success">Confirmada</span>
                            <span v-else-if="reserva.estado === 'pendiente'" class="badge bg-warning text-dark">Pendiente</span>
                            <span v-else-if="reserva.estado === 'cancelada'" class="badge bg-danger">Cancelada</span>
                            <span v-else class="badge bg-secondary">Finalizada</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        // props: datos recibidos del padre (DashboardView)
        // reservas: array con las últimas 5 reservas, viene del endpoint dashboard_back.php
        // Este componente es de solo lectura, no emite eventos (no tiene botones de acción)
        props: ['reservas']
    }
</script>