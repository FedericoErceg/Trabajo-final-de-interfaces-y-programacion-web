<template>
    <div class="d-flex">
        <SidebarComponent currentPage="reservas" />

        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1>Reservas</h1>
                    <p class="text-muted mb-0">Gestión de reservas del hotel</p>
                </div>
                <button class="btn btn-hotel" @click="abrirModalNueva">
                    <i class="bi bi-plus-lg me-2"></i>Nueva reserva
                </button>
            </div>

            <!-- Filtros -->
            <div class="content-card mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label form-label-sm">Buscar pasajero</label>
                        <input type="text" class="form-control" v-model="filtroBuscar" placeholder="DNI, nombre o apellido...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm">Estado</label>
                        <select class="form-select" v-model="filtroEstado">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                            <option value="finalizada">Finalizada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm">Desde</label>
                        <input type="date" class="form-control" v-model="filtroDesde">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm">Hasta</label>
                        <input type="date" class="form-control" v-model="filtroHasta">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-hotel flex-grow-1">
                            <i class="bi bi-search me-1"></i>Filtrar
                        </button>
                        <button class="btn btn-outline-secondary" @click="limpiarFiltros">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            <TablaReservasComponent
                :reservas="reservasFiltradas"
                @editar="abrirModalEditar"
                @eliminar="abrirModalEliminar"
            />
        </main>
    </div>

    <ModalReservaComponent
        :titulo="modalTitulo"
        v-model:form="form"
        :habitaciones="habitaciones"
        :totalEstimado="totalEstimado"
        @guardar="guardarReserva"
    />

    <ModalEliminarComponent
        modalId="modalEliminarReserva"
        titulo="¿Eliminar reserva?"
        mensaje="Esta acción no se puede deshacer."
        @confirmar="eliminarReserva"
    />
</template>

<script>
    import SidebarComponent from '@/components/SidebarComponent.vue'
    import TablaReservasComponent from '@/components/TablaReservasComponent.vue'
    import ModalReservaComponent from '@/components/ModalReservaComponent.vue'
    import ModalEliminarComponent from '@/components/ModalEliminarComponent.vue'
    import { Modal } from 'bootstrap'

    export default {
        components: {
            SidebarComponent,
            TablaReservasComponent,
            ModalReservaComponent,
            ModalEliminarComponent
        },
        data() {
            return {
                reservas: [],
                habitaciones: [],
                form: {
                    id: '',
                    id_habitacion: '',
                    dni: '',
                    nombre: '',
                    apellido: '',
                    telefono: '',
                    email: '',
                    domicilio: '',
                    provincia: '',
                    pais: 'Argentina',
                    fecha_entrada: '',
                    fecha_salida: '',
                    observacion: '',
                    total: ''
                },
                filtroBuscar: '',
                filtroEstado: '',
                filtroDesde: '',
                filtroHasta: '',
                modalTitulo: 'Nueva reserva',
                idEliminar: null
            }
        },
        computed: {
            reservasFiltradas() {
                return this.reservas.filter(res => {
                    if (this.filtroBuscar) {
                        const buscar = this.filtroBuscar.toLowerCase()
                        const coincide = res.nombre.toLowerCase().includes(buscar) ||
                            res.apellido.toLowerCase().includes(buscar) ||
                            res.dni.includes(this.filtroBuscar)
                        if (!coincide) return false
                    }
                    if (this.filtroEstado && res.estado !== this.filtroEstado) return false
                    if (this.filtroDesde && res.fecha_entrada < this.filtroDesde) return false
                    if (this.filtroHasta && res.fecha_salida > this.filtroHasta) return false
                    return true
                })
            },
            totalEstimado() {
                if (!this.form.id_habitacion || !this.form.fecha_entrada || !this.form.fecha_salida) return '$0'
                const hab = this.habitaciones.find(h => h.id_habitacion == this.form.id_habitacion)
                if (!hab) return '$0'
                const entrada = new Date(this.form.fecha_entrada)
                const salida = new Date(this.form.fecha_salida)
                const noches = Math.ceil((salida - entrada) / (1000 * 60 * 60 * 24))
                if (noches <= 0) return 'Fechas inválidas'
                const total = noches * hab.precio_noche
                return '$' + total.toLocaleString('es-AR') + ' (' + noches + ' noches)'
            }
        },
        async mounted() {
            await this.cargarReservas()
            await this.cargarHabitaciones()
        },
        methods: {
            async cargarReservas() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/reservas/reservas_back.php')
                const data = await res.json()
                this.reservas = data
            },
            async cargarHabitaciones() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/habitaciones_back.php')
                const data = await res.json()
                this.habitaciones = data
            },
            abrirModalNueva() {
                this.modalTitulo = 'Nueva reserva'
                this.form = {
                    id: '', id_habitacion: '', dni: '', nombre: '', apellido: '',
                    telefono: '', email: '', domicilio: '', provincia: '',
                    pais: 'Argentina', fecha_entrada: '', fecha_salida: '',
                    observacion: '', total: ''
                }
                this.modalReserva = new Modal(document.getElementById('modalReserva'))
                this.modalReserva.show()
            },
            abrirModalEditar(res) {
                this.modalTitulo = 'Editar reserva'
                this.form = {
                    id: res.id_reserva,
                    id_habitacion: res.id_habitacion,
                    dni: res.dni,
                    nombre: res.nombre,
                    apellido: res.apellido,
                    telefono: res.telefono,
                    email: res.email,
                    domicilio: res.domicilio,
                    provincia: res.provincia,
                    pais: res.pais,
                    fecha_entrada: res.fecha_entrada,
                    fecha_salida: res.fecha_salida,
                    observacion: res.observacion,
                    total: res.total
                }
                this.modalReserva = new Modal(document.getElementById('modalReserva'))
                this.modalReserva.show()
            },
            async guardarReserva() {
                // Calcular total antes de enviar
                const hab = this.habitaciones.find(h => h.id_habitacion == this.form.id_habitacion)
                if (hab) {
                    const entrada = new Date(this.form.fecha_entrada)
                    const salida = new Date(this.form.fecha_salida)
                    const noches = Math.ceil((salida - entrada) / (1000 * 60 * 60 * 24))
                    this.form.total = noches * hab.precio_noche
                }

                const url = this.form.id
                    ? 'http://localhost/ProyectoFinal-FedericoErceg/backend/reservas/put_reservas.php'
                    : 'http://localhost/ProyectoFinal-FedericoErceg/backend/reservas/post_reservas.php'

                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(this.form)
                })
                const data = await res.json()

                if (data.exito) {
                    this.modalReserva.hide()
                    await this.cargarReservas()
                } else {
                    alert(data.mensaje)
                }
            },
            abrirModalEliminar(id) {
                this.idEliminar = id
                this.modalEliminar = new Modal(document.getElementById('modalEliminarReserva'))
                this.modalEliminar.show()
            },
            async eliminarReserva() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/reservas/delete_reservas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id: this.idEliminar })
                })
                const data = await res.json()

                if (data.exito) {
                    this.modalEliminar.hide()
                    await this.cargarReservas()
                } else {
                    alert(data.mensaje)
                }
            },
            limpiarFiltros() {
                this.filtroBuscar = ''
                this.filtroEstado = ''
                this.filtroDesde = ''
                this.filtroHasta = ''
            }
        }
    }
</script>