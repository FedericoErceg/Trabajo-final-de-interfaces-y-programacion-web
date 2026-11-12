<template>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Número</th>
                        <th>Piso</th>
                        <th>Categoría</th>
                        <th>Capacidad</th>
                        <th>Precio/noche</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="habitaciones.length === 0">
                        <td colspan="8" class="text-center text-muted py-4">No hay habitaciones registradas</td>
                    </tr>
                    <tr v-for="hab in habitaciones" :key="hab.id_habitacion">
                        <td>{{ hab.id_habitacion }}</td>
                        <td>{{ hab.numero }}</td>
                        <td>{{ hab.piso }}</td>
                        <td>{{ hab.tipo }}</td>
                        <td>{{ hab.capacidad }} Personas</td>
                        <td>${{ Number(hab.precio_noche).toLocaleString('es-AR') }}</td>
                        <td>
                            <span v-if="hab.estado === 'disponible'" class="badge bg-success">Disponible</span>
                            <span v-else class="badge bg-warning text-dark">Mantenimiento</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary btn-accion" @click="$emit('editar', hab)"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-accion" @click="$emit('eliminar', hab.id_habitacion)"><i class="bi bi-trash"></i></button>
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
        props: ['habitaciones'],
        emits: ['editar', 'eliminar']
    }
</script>