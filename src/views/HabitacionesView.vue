<template>
    <div class="d-flex">
        <SidebarComponent currentPage="habitaciones" />

        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1>Habitaciones</h1>
                    <p class="text-muted mb-0">Gestión de habitaciones del hotel</p>
                </div>
                <button class="btn btn-hotel" @click="abrirModalNueva">
                    <i class="bi bi-plus-lg me-2"></i>Nueva habitación
                </button>
            </div>

            <!-- Filtros -->
            <div class="content-card mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label form-label-sm">Buscar por número</label>
                        <input type="text" class="form-control" v-model="filtroNumero" placeholder="Ej: 301">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm">Categoría</label>
                        <select class="form-select" v-model="filtroCategoria">
                            <option value="">Todas</option>
                            <option value="Single">Single</option>
                            <option value="Doble Simple">Doble Simple</option>
                            <option value="Doble Superior">Doble Superior</option>
                            <option value="Doble Suite">Doble Suite</option>
                            <option value="Triple Simple">Triple Simple</option>
                            <option value="Triple Superior">Triple Superior</option>
                            <option value="Triple Suite">Triple Suite</option>
                            <option value="Cuádruple">Cuádruple</option>
                            <option value="Departamento">Departamento</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm">Piso</label>
                        <select class="form-select" v-model="filtroPiso">
                            <option value="">Todos</option>
                            <option value="1">Piso 1</option>
                            <option value="2">Piso 2</option>
                            <option value="3">Piso 3</option>
                            <option value="4">Piso 4</option>
                            <option value="5">Piso 5</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm">Estado</label>
                        <select class="form-select" v-model="filtroEstado">
                            <option value="">Todos</option>
                            <option value="disponible">Disponible</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
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

            <TablaHabitacionesComponent
                :habitaciones="habitacionesFiltradas"
                @editar="abrirModalEditar"
                @eliminar="abrirModalEliminar"
            />
        </main>
    </div>

    <ModalHabitacionComponent
        :titulo="modalTitulo"
        v-model:form="form"
        @guardar="guardarHabitacion"
    />

    <ModalEliminarComponent
        modalId="modalEliminar"
        titulo="¿Eliminar habitación?"
        mensaje="No se puede eliminar si tiene reservas asociadas."
        @confirmar="eliminarHabitacion"
    />
</template>

<script>
import SidebarComponent from '@/components/SidebarComponent.vue'
import TablaHabitacionesComponent from '@/components/TablaHabitacionesComponent.vue'
import ModalHabitacionComponent from '@/components/ModalHabitacionComponent.vue'
import ModalEliminarComponent from '@/components/ModalEliminarComponent.vue'
import { Modal } from 'bootstrap'

export default {
        components: {
            SidebarComponent,
            TablaHabitacionesComponent,
            ModalHabitacionComponent,
            ModalEliminarComponent
        },

        data() {
            return {
                habitaciones: [],
                form: {
                    id: '',
                    numero: '',
                    piso: '',
                    id_categoria_habitacion: '',
                    estado: 'disponible'
                },
                filtroNumero: '',
                filtroCategoria: '',
                filtroPiso: '',
                filtroEstado: '',
                modalTitulo: 'Nueva habitación',
                idEliminar: null
            }
        },

        computed: {
            habitacionesFiltradas() {
                return this.habitaciones.filter(hab => {
                    if (this.filtroNumero && !hab.numero.includes(this.filtroNumero)) return false
                    if (this.filtroCategoria && hab.tipo !== this.filtroCategoria) return false
                    if (this.filtroPiso && hab.piso != this.filtroPiso) return false
                    if (this.filtroEstado && hab.estado !== this.filtroEstado) return false
                    return true
                })
            }
        },

        async mounted() {
            await this.cargarHabitaciones()
        },

        methods: {
            async cargarHabitaciones() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/habitaciones_back.php')
                const data = await res.json()
                this.habitaciones = data
            },

            abrirModalNueva() {
                this.modalTitulo = 'Nueva habitación'
                this.form = { id: '', numero: '', piso: '', id_categoria_habitacion: '', estado: 'disponible' }
                const modal = new Modal(document.getElementById('modalHabitacion'))
                modal.show()
            },

            abrirModalEditar(hab) {
                this.modalTitulo = 'Editar habitación'
                this.form = {
                    id: hab.id_habitacion,
                    numero: hab.numero,
                    piso: hab.piso,
                    id_categoria_habitacion: hab.id_categoria_habitacion,
                    estado: hab.estado
                }
                const modal = new Modal(document.getElementById('modalHabitacion'))
                modal.show()
            },

            async guardarHabitacion() {
                const url = this.form.id
                    ? 'http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/put_habitaciones.php'
                    : 'http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/post_habitaciones.php'

                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(this.form)
                })
                const data = await res.json()

                if (data.exito) {
                    const modal = Modal.getInstance(document.getElementById('modalHabitacion'))
                    modal.hide()
                    await this.cargarHabitaciones()
                } else {
                    alert(data.mensaje)
                }
            },

            abrirModalEliminar(id) {
                this.idEliminar = id
                const modal = new Modal(document.getElementById('modalEliminar'))
                modal.show()
            },
            
            async eliminarHabitacion() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/delete_habitaciones.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id: this.idEliminar })
                })
                const data = await res.json()

                if (data.exito) {
                    const modal = Modal.getInstance(document.getElementById('modalEliminar'))
                    modal.hide()
                    await this.cargarHabitaciones()
                } else {
                    alert(data.mensaje)
                }
            },
            
            limpiarFiltros() {
                this.filtroNumero = ''
                this.filtroCategoria = ''
                this.filtroPiso = ''
                this.filtroEstado = ''
            }
        }
    }
</script>