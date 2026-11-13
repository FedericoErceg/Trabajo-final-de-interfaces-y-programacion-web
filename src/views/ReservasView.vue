<template>
    <!-- Layout con flexbox: sidebar a la izquierda, contenido a la derecha -->
    <div class="d-flex">
        <SidebarComponent currentPage="reservas" />

        <main class="main-content">
            <!-- Encabezado con título y botón de crear -->
            <div class="content-header">
                <div>
                    <h1>Reservas</h1>
                    <p class="text-muted mb-0">Gestión de reservas del hotel</p>
                </div>
                <button class="btn btn-hotel" @click="abrirModalNueva">
                    <i class="bi bi-plus-lg me-2"></i>Nueva reserva
                </button>
            </div>

            <!-- Filtros: cada input usa v-model para vincular su valor a una variable reactiva -->
            <!-- El computed reservasFiltradas filtra automáticamente cuando cambian estos valores -->
            <div class="content-card mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label form-label-sm">Buscar pasajero</label>
                        <!-- Busca por DNI, nombre o apellido (se evalúa en el computed) -->
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
                        <!-- Filtra reservas cuya fecha_entrada sea >= a esta fecha -->
                        <input type="date" class="form-control" v-model="filtroDesde">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm">Hasta</label>
                        <!-- Filtra reservas cuya fecha_salida sea <= a esta fecha -->
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

            <!-- Tabla de reservas: recibe el array filtrado por el computed -->
            <!-- @editar y @eliminar escuchan los $emit del componente hijo -->
            <TablaReservasComponent
                :reservas="reservasFiltradas"
                @editar="abrirModalEditar"
                @eliminar="abrirModalEliminar"
            />
        </main>
    </div>

    <!-- Modal crear/editar reserva -->
    <!-- v-model:form hace binding bidireccional del formulario -->
    <!-- :habitaciones pasa el listado de habitaciones para el select del modal -->
    <!-- :totalEstimado pasa el cálculo en tiempo real del precio total -->
    <ModalReservaComponent
        :titulo="modalTitulo"
        v-model:form="form"
        :habitaciones="habitaciones"
        :totalEstimado="totalEstimado"
        @guardar="guardarReserva"
    />

    <!-- Modal de confirmación de eliminación (componente genérico reutilizable) -->
    <!-- modalId diferente al de habitaciones/categorías para que no se pisen -->
    <ModalEliminarComponent
        modalId="modalEliminarReserva"
        titulo="¿Eliminar reserva?"
        mensaje="Esta acción no se puede deshacer."
        @confirmar="eliminarReserva"
    />
</template>

<script>
    // Importación de componentes
    import SidebarComponent from '@/components/SidebarComponent.vue'
    import TablaReservasComponent from '@/components/TablaReservasComponent.vue'
    import ModalReservaComponent from '@/components/ModalReservaComponent.vue'
    import ModalEliminarComponent from '@/components/ModalEliminarComponent.vue'
    import { Modal } from 'bootstrap'

    export default {

        // Registro de componentes usados en el template
        components: {
            SidebarComponent,
            TablaReservasComponent,
            ModalReservaComponent,
            ModalEliminarComponent
        },

        // Estado reactivo del componente
        data() {
            return {
                reservas: [],           // Array de reservas traídas del backend
                habitaciones: [],       // Array de habitaciones para el select del modal
                form: {                 // Objeto del formulario vinculado al modal con v-model
                    id: '',
                    id_habitacion: '',
                    dni: '',
                    nombre: '',
                    apellido: '',
                    telefono: '',
                    email: '',
                    domicilio: '',
                    provincia: '',
                    pais: 'Argentina',      // Valor por defecto
                    fecha_entrada: '',
                    fecha_salida: '',
                    observacion: '',
                    total: '',
                    estado: 'pendiente'     // Las reservas nuevas arrancan como pendientes
                },
                filtroBuscar: '',       // Variables de filtro vinculadas con v-model
                filtroEstado: '',
                filtroDesde: '',
                filtroHasta: '',
                modalTitulo: 'Nueva reserva',   // Cambia entre "Nueva" y "Editar"
                idEliminar: null                 // ID de la reserva a eliminar
            }
        },

        // Propiedades calculadas: se recalculan automáticamente cuando cambian sus dependencias
        computed: {
            // Filtra las reservas según los valores de los filtros
            // Busca por nombre, apellido o DNI, y filtra por estado y rango de fechas
            reservasFiltradas() {
                return this.reservas.filter(res => {
                    // Filtro de búsqueda: busca en nombre, apellido y DNI
                    if (this.filtroBuscar) {
                        const buscar = this.filtroBuscar.toLowerCase()
                        const coincide = res.nombre.toLowerCase().includes(buscar) ||
                            res.apellido.toLowerCase().includes(buscar) ||
                            res.dni.includes(this.filtroBuscar)     // DNI se busca sin toLowerCase porque es numérico
                        if (!coincide) return false
                    }
                    if (this.filtroEstado && res.estado !== this.filtroEstado) return false
                    if (this.filtroDesde && res.fecha_entrada < this.filtroDesde) return false
                    if (this.filtroHasta && res.fecha_salida > this.filtroHasta) return false
                    return true
                })
            },

            // Calcula el precio total en tiempo real mientras el usuario selecciona habitación y fechas
            // Se recalcula automáticamente cada vez que cambian id_habitacion, fecha_entrada o fecha_salida
            totalEstimado() {
                // Si falta algún dato necesario, devuelve $0
                if (!this.form.id_habitacion || !this.form.fecha_entrada || !this.form.fecha_salida) return '$0'
                // find() busca la habitación seleccionada para obtener su precio_noche
                const hab = this.habitaciones.find(h => h.id_habitacion == this.form.id_habitacion)
                if (!hab) return '$0'
                // Calcula la diferencia en días entre entrada y salida
                const entrada = new Date(this.form.fecha_entrada)
                const salida = new Date(this.form.fecha_salida)
                const noches = Math.ceil((salida - entrada) / (1000 * 60 * 60 * 24))   // Milisegundos a días
                if (noches <= 0) return 'Fechas inválidas'
                // Multiplica noches por precio y formatea con separador de miles
                const total = noches * hab.precio_noche
                return '$' + total.toLocaleString('es-AR') + ' (' + noches + ' noches)'
            }
        },

        // Se ejecuta al montar el componente: carga reservas y habitaciones del backend
        async mounted() {
            await this.cargarReservas()
            await this.cargarHabitaciones()      // Se necesitan para el select del modal
        },

        methods: {
            // GET - Trae todas las reservas con el número de habitación (JOIN en el backend)
            async cargarReservas() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/reservas/reservas_back.php')
                const data = await res.json()
                this.reservas = data
            },
            // GET - Trae las habitaciones para llenar el select del modal de reservas
            async cargarHabitaciones() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/habitaciones_back.php')
                const data = await res.json()
                this.habitaciones = data
            },
            // Prepara el modal para crear una nueva reserva con valores vacíos
            abrirModalNueva() {
                this.modalTitulo = 'Nueva reserva'
                this.form = {
                    id: '', id_habitacion: '', dni: '', nombre: '', apellido: '',
                    telefono: '', email: '', domicilio: '', provincia: '',
                    pais: 'Argentina', fecha_entrada: '', fecha_salida: '',
                    observacion: '', total: '', estado: 'pendiente'
                }
                this.modalReserva = new Modal(document.getElementById('modalReserva'))
                this.modalReserva.show()
            },

            // Prepara el modal para editar, llenando el form con los datos de la reserva seleccionada
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
                    total: res.total,
                    estado: res.estado      // Permite cambiar el estado (pendiente → confirmada, etc.)
                }
                this.modalReserva = new Modal(document.getElementById('modalReserva'))
                this.modalReserva.show()
            },

            // POST o PUT - Guarda la reserva (nueva o editada)
            async guardarReserva() {
                // Validación de campos obligatorios
                if (!this.form.id_habitacion || !this.form.dni || !this.form.nombre || !this.form.apellido || !this.form.fecha_entrada || !this.form.fecha_salida) {
                    alert('Completá los campos obligatorios')
                } else {
                    // Calcula el total antes de enviar al backend
                    // Busca el precio_noche de la habitación seleccionada y multiplica por las noches
                    const hab = this.habitaciones.find(h => h.id_habitacion == this.form.id_habitacion)
                    if (hab) {
                        const entrada = new Date(this.form.fecha_entrada)
                        const salida = new Date(this.form.fecha_salida)
                        const noches = Math.ceil((salida - entrada) / (1000 * 60 * 60 * 24))
                        this.form.total = noches * hab.precio_noche
                    }

                    // Si form.id tiene valor → edición (PUT), si está vacío → creación (POST)
                    const url = this.form.id
                        ? 'http://localhost/ProyectoFinal-FedericoErceg/backend/reservas/put_reservas.php'
                        : 'http://localhost/ProyectoFinal-FedericoErceg/backend/reservas/post_reservas.php'

                    // Envía los datos al backend como formulario URL-encoded
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams(this.form)
                    })
                    const data = await res.json()

                    if (data.exito) {
                        this.modalReserva.hide()   // Cierra el modal
                        await this.cargarReservas()    // Recarga la tabla actualizada
                    } else {
                        alert(data.mensaje)    // Ej: "habitación no disponible en esas fechas"
                    }
                }
            },

            // Prepara el modal de confirmación de eliminación
            abrirModalEliminar(id) {
                this.idEliminar = id
                // Usa modalEliminarReserva como ID para no pisarse con el de habitaciones/categorías
                this.modalEliminar = new Modal(document.getElementById('modalEliminarReserva'))
                this.modalEliminar.show()
            },

            // DELETE - Elimina la reserva confirmada
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

            // Resetea todos los filtros, lo que muestra todas las reservas
            limpiarFiltros() {
                this.filtroBuscar = ''
                this.filtroEstado = ''
                this.filtroDesde = ''
                this.filtroHasta = ''
            }
        }
    }
</script>