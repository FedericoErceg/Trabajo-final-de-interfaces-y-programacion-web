<template>
    <div class="modal fade" id="modalHabitacion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ titulo }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" :value="form.numero" @input="$emit('update:form', { ...form, numero: $event.target.value })" placeholder="Ej: 301" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Piso</label>
                            <input type="number" class="form-control" :value="form.piso" @input="$emit('update:form', { ...form, piso: $event.target.value })" min="1" max="5" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" :value="form.id_categoria_habitacion" @change="$emit('update:form', { ...form, id_categoria_habitacion: $event.target.value })" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">Single (1 pers.)</option>
                                <option value="2">Doble Simple (2 pers.)</option>
                                <option value="3">Doble Superior (2 pers.)</option>
                                <option value="4">Doble Suite (2 pers.)</option>
                                <option value="5">Triple Simple (3 pers.)</option>
                                <option value="6">Triple Superior (3 pers.)</option>
                                <option value="7">Triple Suite (3 pers.)</option>
                                <option value="8">Cuádruple (4 pers.)</option>
                                <option value="9">Departamento (5 pers.)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" :value="form.estado" @change="$emit('update:form', { ...form, estado: $event.target.value })" required>
                                <option value="disponible">Disponible</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
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