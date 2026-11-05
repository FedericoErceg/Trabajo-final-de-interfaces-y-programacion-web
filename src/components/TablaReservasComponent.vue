<template>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pasajero</th>
                        <th>DNI</th>
                        <th>Habitación</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="res in reservas" :key="res.id_reserva">
                        <td>{{ res.id_reserva }}</td>
                        <td>{{ res.nombre }} {{ res.apellido }}</td>
                        <td>{{ res.dni }}</td>
                        <td>{{ res.numero }}</td>
                        <td>{{ res.fecha_entrada }}</td>
                        <td>{{ res.fecha_salida }}</td>
                        <td>${{ res.total }}</td>
                        <td>
                            <span v-if="res.estado === 'confirmada'" class="badge bg-success">Confirmada</span>
                            <span v-else-if="res.estado === 'pendiente'" class="badge bg-warning text-dark">Pendiente</span>
                            <span v-else-if="res.estado === 'cancelada'" class="badge bg-danger">Cancelada</span>
                            <span v-else class="badge bg-secondary">Finalizada</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary btn-accion" @click="$emit('editar', res)"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-accion" @click="$emit('eliminar', res.id_reserva)"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['reservas'],
        emits: ['editar', 'eliminar']
    }
</script>