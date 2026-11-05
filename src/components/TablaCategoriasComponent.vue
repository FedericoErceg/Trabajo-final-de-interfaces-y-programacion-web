<template>
    <div class="content-card">
        <div class="table-responsive">
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
                    <tr v-for="cat in categorias" :key="cat.id_categoria">
                        <td>{{ cat.id_categoria }}</td>
                        <td>{{ cat.tipo }}</td>
                        <td>{{ cat.capacidad }} Personas</td>
                        <td>${{ cat.precio_noche }}</td>
                        <td>{{ cat.descripcion }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary btn-accion" @click="$emit('editar', cat)"><i class="bi bi-pencil"></i></button>
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
        props: ['categorias'],
        emits: ['editar', 'eliminar']
    }
</script>