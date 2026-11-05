<template>
    <div class="modal fade" id="modalCategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ titulo }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" :value="form.tipo" @input="$emit('update:form', { ...form, tipo: $event.target.value })" placeholder="Ej: Doble Simple" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidad (personas)</label>
                            <input type="number" class="form-control" :value="form.capacidad" @input="$emit('update:form', { ...form, capacidad: $event.target.value })" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio por noche ($)</label>
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
        props: ['titulo', 'form'],
        emits: ['guardar', 'update:form']
    }
</script>