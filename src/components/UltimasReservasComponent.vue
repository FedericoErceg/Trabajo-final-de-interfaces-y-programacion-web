<template>
    <div class="content-card">
        <div class="content-card-header">
            <h5>Últimas reservas</h5>
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
                    <tr v-for="reserva in reservas" :key="reserva.id_reserva">
                        <td>{{ reserva.nombre }} {{ reserva.apellido }}</td>
                        <td>{{ reserva.numero }}</td>
                        <td>{{ reserva.fecha_entrada }}</td>
                        <td>{{ reserva.fecha_salida }}</td>
                        <td>
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
        props: ['reservas']
    }
</script>