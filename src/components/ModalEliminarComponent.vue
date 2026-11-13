<template>
    <!-- Modal genérico de confirmación de eliminación, reutilizable en habitaciones, categorías y reservas -->
    <!-- :id es dinámico (viene por prop) para que cada vista tenga su propio modal sin pisarse -->
    <!-- Ej: "modalEliminar" en habitaciones/categorías, "modalEliminarReserva" en reservas -->
    <div class="modal fade" :id="modalId" tabindex="-1">
        <!-- modal-sm: tamaño chico, modal-dialog-centered: centrado verticalmente en la pantalla -->
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <!-- Ícono de advertencia de Bootstrap Icons -->
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 48px;"></i>
                    <!-- titulo y mensaje vienen por props, personalizados según qué se está eliminando -->
                    <!-- Ej: titulo="¿Eliminar habitación?" mensaje="No se puede eliminar si tiene reservas asociadas." -->
                    <h5 class="mt-3">{{ titulo }}</h5>
                    <p class="text-muted">{{ mensaje }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <!-- data-bs-dismiss="modal" cierra el modal sin hacer nada (cancela) -->
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!-- @click emite 'confirmar' al padre, que ejecuta el método de eliminación correspondiente -->
                    <button type="button" class="btn btn-danger" @click="$emit('confirmar')">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        // modalId: ID único del modal (string), para que Bootstrap lo identifique
        // titulo: texto de la pregunta de confirmación
        // mensaje: texto adicional con advertencia
        props: ['modalId', 'titulo', 'mensaje'],
        // emits: evento que avisa al padre que el usuario confirmó la eliminación
        emits: ['confirmar']
    }
</script>