<template>
    <div class="modal fade" id="modalReserva" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ titulo }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="form-section-title">Datos del pasajero</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control" :value="form.dni" @input="actualizar('dni', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" :value="form.nombre" @input="actualizar('nombre', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" :value="form.apellido" @input="actualizar('apellido', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" :value="form.telefono" @input="actualizar('telefono', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" :value="form.email" @input="actualizar('email', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Domicilio</label>
                            <input type="text" class="form-control" :value="form.domicilio" @input="actualizar('domicilio', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provincia</label>
                            <input type="text" class="form-control" :value="form.provincia" @input="actualizar('provincia', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">País</label>
                            <input type="text" class="form-control" :value="form.pais" @input="actualizar('pais', $event.target.value)">
                        </div>
                    </div>

                    <h6 class="form-section-title">Datos de la reserva</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Habitación</label>
                            <select class="form-select" :value="form.id_habitacion" @change="actualizar('id_habitacion', $event.target.value)">
                                <option value="">Seleccionar...</option>
                                <option v-for="hab in habitaciones" :key="hab.id_habitacion" :value="hab.id_habitacion">
                                    {{ hab.numero }} - {{ hab.tipo }} (${{ hab.precio_noche }})
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha de entrada</label>
                            <input type="date" class="form-control" :value="form.fecha_entrada" @input="actualizar('fecha_entrada', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha de salida</label>
                            <input type="date" class="form-control" :value="form.fecha_salida" @input="actualizar('fecha_salida', $event.target.value)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total estimado</label>
                            <div class="form-control bg-light">{{ totalEstimado }}</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Observación</label>
                            <textarea class="form-control" :value="form.observacion" @input="actualizar('observacion', $event.target.value)" rows="1"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-hotel" @click="$emit('guardar')">
                        <i class="bi bi-check-lg me-1"></i>Guardar reserva
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['titulo', 'form', 'habitaciones', 'totalEstimado'],
    emits: ['guardar', 'update:form'],
    methods: {
        actualizar(campo, valor) {
            this.$emit('update:form', { ...this.form, [campo]: valor })
        }
    }
}
</script>

<style scoped>
.form-section-title {
    font-size: 14px;
    font-weight: 600;
    color: #8b7355;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e5e5e5;
}
</style>