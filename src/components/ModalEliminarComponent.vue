<template>
    <div class="modal fade" :id="modalId" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 48px;"></i>
                    <h5 class="mt-3">{{ titulo }}</h5>
                    <p class="text-muted">{{ mensaje }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" @click="$emit('confirmar')">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['modalId', 'titulo', 'mensaje'],
        emits: ['confirmar']
    }
</script>