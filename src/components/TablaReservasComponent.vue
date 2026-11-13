<template>
    <!-- Tabla que muestra las reservas del hotel -->
    <!-- Recibe el array ya filtrado desde el padre (ReservasView) -->
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
                    <!-- Mensaje cuando no hay reservas (array vacío o filtro sin resultados) -->
                    <tr v-if="reservas.length === 0">
                        <td colspan="9" class="text-center text-muted py-4">No hay reservas registradas</td>
                    </tr>
                    <!-- v-for genera una fila por cada reserva del array -->
                    <tr v-for="res in reservas" :key="res.id_reserva">
                        <td>{{ res.id_reserva }}</td>
                        <!-- Concatena nombre y apellido del pasajero en una sola celda -->
                        <td>{{ res.nombre }} {{ res.apellido }}</td>
                        <td>{{ res.dni }}</td>
                        <!-- res.numero viene del JOIN con la tabla habitaciones en el backend -->
                        <td>{{ res.numero }}</td>
                        <td>{{ res.fecha_entrada }}</td>
                        <td>{{ res.fecha_salida }}</td>
                        <td>${{ res.total }}</td>
                        <td>
                            <!-- v-if / v-else-if / v-else renderiza un badge distinto según el estado -->
                            <!-- Cada estado tiene un color diferente para identificarlo rápido -->
                            <span v-if="res.estado === 'confirmada'" class="badge bg-success">Confirmada</span>
                            <span v-else-if="res.estado === 'pendiente'" class="badge bg-warning text-dark">Pendiente</span>
                            <span v-else-if="res.estado === 'cancelada'" class="badge bg-danger">Cancelada</span>
                            <!-- v-else captura cualquier otro estado (finalizada) -->
                            <span v-else class="badge bg-secondary">Finalizada</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- @click emite 'editar' al padre enviando el objeto reserva completo -->
                                <!-- El padre lo usa para llenar el modal con todos los datos del pasajero y la reserva -->
                                <button class="btn btn-sm btn-outline-primary btn-accion" @click="$emit('editar', res)"><i class="bi bi-pencil"></i></button>
                                <!-- @click emite 'eliminar' enviando solo el ID -->
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
        // props: datos recibidos del padre (ReservasView)
        // reservas: array de objetos ya filtrado por el computed reservasFiltradas del padre
        // Cada objeto tiene datos del pasajero (nombre, apellido, dni) y de la reserva (fechas, total, estado)
        props: ['reservas'],
        // emits: eventos que este componente envía al padre
        // 'editar': envía el objeto reserva completo para llenar el modal de edición
        // 'eliminar': envía el ID para abrir el modal de confirmación
        emits: ['editar', 'eliminar']
    }
</script>