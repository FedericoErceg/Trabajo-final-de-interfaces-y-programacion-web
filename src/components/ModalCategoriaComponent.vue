<template>
    <!-- Modal de Bootstrap para crear/editar categorías -->
    <!-- id="modalCategoria" se usa desde el padre para abrir/cerrar con new Modal() -->
    <div class="modal fade" id="modalCategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- titulo viene por prop, cambia entre "Nueva categoría" y "Editar categoría" -->
                    <h5 class="modal-title">{{ titulo }}</h5>
                    <!-- data-bs-dismiss="modal" es de Bootstrap, cierra el modal sin JavaScript -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <!-- :value muestra el valor actual del form (viene del padre por prop) -->
                            <!-- @input emite 'update:form' con el objeto form actualizado -->
                            <!-- ...form (spread) copia todas las propiedades y solo cambia la que se modificó -->
                            <!-- Esto completa el patrón v-model:form bidireccional con el padre -->
                            <input type="text" class="form-control" :value="form.tipo" @input="$emit('update:form', { ...form, tipo: $event.target.value })" placeholder="Ej: Doble Simple" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidad (personas)</label>
                            <input type="number" class="form-control" :value="form.capacidad" @input="$emit('update:form', { ...form, capacidad: $event.target.value })" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio por noche ($)</label>
                            <!-- step="0.01" permite ingresar decimales en el precio -->
                            <input type="number" class="form-control" :value="form.precio_noche" @input="$emit('update:form', { ...form, precio_noche: $event.target.value })" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" :value="form.descripcion" @input="$emit('update:form', { ...form, descripcion: $event.target.value })" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!-- @click emite 'guardar' al padre, que ejecuta guardarCategoria() -->
                    <button type="button" class="btn btn-hotel" @click="$emit('guardar')">
                        <i class="bi bi-check-lg me-1"></i>Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
        // props: datos recibidos del padre (CategoriasView)
        // titulo: texto del encabezado del modal
        // form: objeto con los campos del formulario (tipo, capacidad, precio_noche, descripcion)
        props: ['titulo', 'form'],
        // emits: eventos que este componente puede enviar al padre
        // 'guardar': se emite al hacer clic en el botón Guardar
        // 'update:form': se emite en cada @input para actualizar el form en el padre (patrón v-model)
        emits: ['guardar', 'update:form']
    }
</script>