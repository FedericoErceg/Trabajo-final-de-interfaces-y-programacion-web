<template>
    <!-- Modal de Bootstrap para crear/editar reservas -->
    <!-- modal-lg: tamaño grande porque tiene muchos campos (datos del pasajero + datos de la reserva) -->
    <div class="modal fade" id="modalReserva" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- titulo viene por prop, cambia entre "Nueva reserva" y "Editar reserva" -->
                    <h5 class="modal-title">{{ titulo }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Sección 1: Datos del pasajero -->
                    <h6 class="form-section-title">Datos del pasajero</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">DNI</label>
                            <!-- En vez de repetir $emit('update:form', { ...form, campo: valor }) en cada input, -->
                            <!-- se usa el método actualizar() que encapsula esa lógica -->
                            <!-- actualizar('dni', valor) emite el form completo con solo 'dni' cambiado -->
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

                    <!-- Sección 2: Datos de la reserva -->
                    <h6 class="form-section-title">Datos de la reserva</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Habitación</label>
                            <!-- Select dinámico: las opciones se generan con v-for desde el array habitaciones -->
                            <!-- Cada opción muestra: número - tipo ($precio) -->
                            <!-- El value de cada option es el id_habitacion para vincular con la FK -->
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
                            <!-- Campo de solo lectura (no es un input, es un div con estilo de input) -->
                            <!-- totalEstimado es un computed del padre que calcula precio_noche × noches -->
                            <!-- Se recalcula automáticamente al cambiar habitación o fechas -->
                            <div class="form-control bg-light">{{ totalEstimado }}</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Observación</label>
                            <textarea class="form-control" :value="form.observacion" @input="actualizar('observacion', $event.target.value)" rows="1"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <!-- Permite cambiar el estado de la reserva al editar -->
                            <!-- Las nuevas reservas arrancan como "pendiente" (valor por defecto del form) -->
                            <select class="form-select" :value="form.estado" @change="actualizar('estado', $event.target.value)">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="cancelada">Cancelada</option>
                                <option value="finalizada">Finalizada</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!-- @click emite 'guardar' al padre, que ejecuta guardarReserva() -->
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
    // titulo: "Nueva reserva" o "Editar reserva"
    // form: objeto con todos los campos del formulario (datos pasajero + datos reserva)
    // habitaciones: array de habitaciones para llenar el select dinámicamente
    // totalEstimado: string con el cálculo de precio calculado en el padre (computed)
    props: ['titulo', 'form', 'habitaciones', 'totalEstimado'],
    // emits: eventos que este componente envía al padre
    // 'guardar': al hacer clic en Guardar
    // 'update:form': en cada cambio de input, completa el patrón v-model:form
    emits: ['guardar', 'update:form'],
    methods: {
        // Método auxiliar que simplifica la emisión del update:form
        // En vez de repetir $emit('update:form', { ...this.form, campo: valor }) en cada input,
        // se llama actualizar('campo', valor) que hace lo mismo
        // [campo] es una propiedad computada (computed property name de ES6): permite usar una variable como nombre de propiedad
        // ...this.form (spread) copia todas las propiedades existentes y solo sobreescribe la que cambió
        actualizar(campo, valor) {
            this.$emit('update:form', { ...this.form, [campo]: valor })
        }
    }
}
</script>

<style scoped>
/* Título de sección dentro del modal (ej: "DATOS DEL PASAJERO", "DATOS DE LA RESERVA") */
/* Usa el color dorado del hotel y una línea separadora debajo */
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