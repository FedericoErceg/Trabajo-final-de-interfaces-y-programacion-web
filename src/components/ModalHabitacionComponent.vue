<template>
    <!-- Modal de Bootstrap para crear/editar habitaciones -->
    <!-- id="modalHabitacion" se usa desde el padre para abrir/cerrar con new Modal() -->
    <div class="modal fade" id="modalHabitacion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- titulo viene por prop, cambia entre "Nueva habitación" y "Editar habitación" -->
                    <h5 class="modal-title">{{ titulo }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Número</label>
                            <!-- :value muestra el valor actual del form (viene del padre por prop) -->
                            <!-- @input emite 'update:form' con el objeto form actualizado -->
                            <!-- ...form (spread) copia todas las propiedades y solo cambia 'numero' -->
                            <!-- $event.target.value captura lo que el usuario escribió en el input -->
                            <input type="text" class="form-control" :value="form.numero" @input="$emit('update:form', { ...form, numero: $event.target.value })" placeholder="Ej: 301" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Piso</label>
                            <input type="number" class="form-control" :value="form.piso" @input="$emit('update:form', { ...form, piso: $event.target.value })" min="1" max="5" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <!-- En selects se usa @change en vez de @input -->
                            <!-- Los value de cada option corresponden al id_categoria de la tabla categorias_habitacion -->
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
                            <!-- Solo dos estados posibles para una habitación -->
                            <select class="form-select" :value="form.estado" @change="$emit('update:form', { ...form, estado: $event.target.value })" required>
                                <option value="disponible">Disponible</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!-- @click emite 'guardar' al padre, que ejecuta guardarHabitacion() -->
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
        // titulo: "Nueva habitación" o "Editar habitación"
        // form: objeto con los campos del formulario (numero, piso, id_categoria_habitacion, estado)
        props: ['titulo', 'form'],
        
        // 'guardar': cuando se hace clic en Guardar
        // 'update:form': en cada cambio de input, completa el patrón v-model:form bidireccional
        emits: ['guardar', 'update:form']
    }
</script>