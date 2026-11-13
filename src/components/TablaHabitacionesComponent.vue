<template>
    <!-- Tabla que muestra las habitaciones del hotel -->
    <!-- Recibe el array ya filtrado desde el padre (HabitacionesView) -->
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
                    <!-- Mensaje cuando no hay habitaciones (array vacío) -->
                    <!-- colspan="8" ocupa las 8 columnas de la tabla -->
                    <tr v-if="habitaciones.length === 0">
                        <td colspan="8" class="text-center text-muted py-4">No hay habitaciones registradas</td>
                    </tr>
                    <!-- v-for genera una fila por cada habitación del array -->
                    <!-- :key es obligatorio en v-for, identifica cada fila de forma única -->
                    <tr v-for="hab in habitaciones" :key="hab.id_habitacion">
                        <td>{{ hab.id_habitacion }}</td>
                        <td>{{ hab.numero }}</td>
                        <td>{{ hab.piso }}</td>
                        <!-- tipo viene del JOIN con categorias_habitacion en el backend -->
                        <td>{{ hab.tipo }}</td>
                        <td>{{ hab.capacidad }} Personas</td>
                        <!-- Number() convierte el string a número para que toLocaleString funcione -->
                        <!-- toLocaleString('es-AR') formatea con separador de miles argentino (ej: 15.000) -->
                        <td>${{ Number(hab.precio_noche).toLocaleString('es-AR') }}</td>
                        <td>
                            <!-- v-if / v-else renderiza condicionalmente el badge según el estado -->
                            <!-- Badge verde para disponible, amarillo para mantenimiento -->
                            <span v-if="hab.estado === 'disponible'" class="badge bg-success">Disponible</span>
                            <span v-else class="badge bg-warning text-dark">Mantenimiento</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- @click emite 'editar' al padre enviando el objeto habitación completo -->
                                <!-- El padre lo recibe en @editar="abrirModalEditar" y llena el modal con esos datos -->
                                <button class="btn btn-sm btn-outline-primary btn-accion" @click="$emit('editar', hab)"><i class="bi bi-pencil"></i></button>
                                <!-- @click emite 'eliminar' enviando solo el ID para el modal de confirmación -->
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
        // props: datos recibidos del padre (HabitacionesView)
        // habitaciones: array de objetos, ya viene filtrado por el computed habitacionesFiltradas del padre
        // Cada objeto tiene: id_habitacion, numero, piso, tipo, capacidad, precio_noche, estado
        props: ['habitaciones'],
        // emits: eventos que este componente puede enviar al padre
        // 'editar': envía el objeto habitación completo para llenar el modal
        // 'eliminar': envía el ID para abrir el modal de confirmación de eliminación
        emits: ['editar', 'eliminar']
    }
</script>