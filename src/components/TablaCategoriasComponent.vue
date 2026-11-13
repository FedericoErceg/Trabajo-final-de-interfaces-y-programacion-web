<template>
    <!-- Tabla que muestra las categorías de habitación -->
    <!-- Componente reutilizable: recibe datos por props y emite eventos al padre -->
    <div class="content-card">
        <!-- table-responsive permite scroll horizontal en pantallas chicas -->
        <div class="table-responsive">
            <!-- table-hover resalta la fila al pasar el mouse -->
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Precio/noche</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- v-if: si el array está vacío, muestra un mensaje en vez de la tabla vacía -->
                    <!-- colspan="6" hace que la celda ocupe todas las columnas -->
                    <tr v-if="categorias.length === 0">
                        <td colspan="6" class="text-center text-muted py-4">No hay categorías registradas</td>
                    </tr>
                    <!-- v-for recorre el array de categorías y genera una fila por cada una -->
                    <!-- :key identifica cada fila de forma única para que Vue optimice el renderizado -->
                    <tr v-for="cat in categorias" :key="cat.id_categoria">
                        <td>{{ cat.id_categoria }}</td>
                        <td>{{ cat.tipo }}</td>
                        <td>{{ cat.capacidad }} Personas</td>
                        <td>${{ cat.precio_noche }}</td>
                        <td>{{ cat.descripcion }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- @click emite el evento 'editar' al padre, enviando el objeto categoría completo -->
                                <!-- El padre (CategoriasView) recibe este evento en @editar="abrirModalEditar" -->
                                <button class="btn btn-sm btn-outline-primary btn-accion" @click="$emit('editar', cat)"><i class="bi bi-pencil"></i></button>
                                <!-- @click emite 'eliminar' enviando solo el ID de la categoría -->
                                <button class="btn btn-sm btn-outline-danger btn-accion" @click="$emit('eliminar', cat.id_categoria)"><i class="bi bi-trash"></i></button>
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
        // props: datos recibidos del padre (CategoriasView)
        // categorias: array de objetos con id_categoria, tipo, capacidad, precio_noche y descripcion
        props: ['categorias'],
        // emits: eventos que este componente puede enviar al padre
        // 'editar': envía el objeto categoría completo para llenar el modal de edición
        // 'eliminar': envía el ID de la categoría para abrir el modal de confirmación
        emits: ['editar', 'eliminar']
    }
</script>