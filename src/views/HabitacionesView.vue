<template>
    <!-- Layout con flexbox: sidebar a la izquierda, contenido a la derecha -->
    <div class="d-flex">
        <!-- Sidebar reutilizable, currentPage="habitaciones" marca el link activo -->
        <SidebarComponent currentPage="habitaciones" />

        <main class="main-content">
            <!-- Encabezado con título y botón de crear -->
            <div class="content-header">
                <div>
                    <h1>Habitaciones</h1>
                    <p class="text-muted mb-0">Gestión de habitaciones del hotel</p>
                </div>
                <button class="btn btn-hotel" @click="abrirModalNueva">
                    <i class="bi bi-plus-lg me-2"></i>Nueva habitación
                </button>
            </div>

            <!-- Filtros: cada input/select usa v-model para vincular su valor a una variable reactiva -->
            <!-- Los filtros funcionan en tiempo real gracias al computed habitacionesFiltradas -->
            <div class="content-card mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label form-label-sm">Buscar por número</label>
                        <!-- v-model vincula el valor del input a la variable filtroNumero -->
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
                        <!-- Llama a limpiarFiltros que resetea todas las variables de filtro -->
                        <button class="btn btn-outline-secondary" @click="limpiarFiltros">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Componente tabla: recibe habitacionesFiltradas (computed) en vez del array completo -->
            <!-- @editar y @eliminar escuchan los $emit del componente hijo -->
            <TablaHabitacionesComponent
                :habitaciones="habitacionesFiltradas"
                @editar="abrirModalEditar"
                @eliminar="abrirModalEliminar"
            />
        </main>
    </div>

    <!-- Modal crear/editar habitación -->
    <!-- v-model:form hace binding bidireccional del objeto formulario -->
    <ModalHabitacionComponent
        :titulo="modalTitulo"
        v-model:form="form"
        @guardar="guardarHabitacion"
    />

    <!-- Modal genérico de confirmación de eliminación -->
    <ModalEliminarComponent
        modalId="modalEliminar"
        titulo="¿Eliminar habitación?"
        mensaje="No se puede eliminar si tiene reservas asociadas."
        @confirmar="eliminarHabitacion"
    />
</template>

<script>
    // Importación de componentes
    import SidebarComponent from '@/components/SidebarComponent.vue'
    import TablaHabitacionesComponent from '@/components/TablaHabitacionesComponent.vue'
    import ModalHabitacionComponent from '@/components/ModalHabitacionComponent.vue'
    import ModalEliminarComponent from '@/components/ModalEliminarComponent.vue'
    // Modal de Bootstrap para abrir/cerrar modales programáticamente
    import { Modal } from 'bootstrap'

    export default {
            // Registro de componentes usados en el template
            components: {
                SidebarComponent,
                TablaHabitacionesComponent,
                ModalHabitacionComponent,
                ModalEliminarComponent
            },

            // Estado reactivo del componente
            data() {
                return {
                    habitaciones: [],    // Array de habitaciones traídas del backend
                    form: {               // Objeto del formulario vinculado al modal
                        id: '',
                        numero: '',
                        piso: '',
                        id_categoria_habitacion: '',
                        estado: 'disponible'
                    },
                    filtroNumero: '',     // Variables de filtro vinculadas con v-model
                    filtroCategoria: '',
                    filtroPiso: '',
                    filtroEstado: '',
                    modalTitulo: 'Nueva habitación',    // Cambia dinámicamente entre "Nueva" y "Editar"
                    idEliminar: null    // ID de la habitación a eliminar
                }
            },

            // Propiedades calculadas: se recalculan automáticamente cuando cambian los filtros
            computed: {
                // Filtra el array de habitaciones según los valores de los filtros
                // filter() devuelve un nuevo array con los elementos que cumplen todas las condiciones
                // Si un filtro está vacío ('') se salta esa condición
                habitacionesFiltradas() {
                    return this.habitaciones.filter(hab => {
                        if (this.filtroNumero && !hab.numero.includes(this.filtroNumero)) return false   // Búsqueda parcial por número
                        if (this.filtroCategoria && hab.tipo !== this.filtroCategoria) return false       // Coincidencia exacta de categoría
                        if (this.filtroPiso && hab.piso != this.filtroPiso) return false                 // != en vez de !== porque piso puede ser string o int
                        if (this.filtroEstado && hab.estado !== this.filtroEstado) return false
                        return true     // Si pasa todos los filtros, se incluye en el resultado
                    })
                }
            },

            // Se ejecuta al montar el componente en el DOM
            async mounted() {
                await this.cargarHabitaciones()     // Trae las habitaciones del backend al cargar
            },

            methods: {
                // GET - Trae todas las habitaciones con sus datos de categoría (JOIN en el backend)
                async cargarHabitaciones() {
                    const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/habitaciones_back.php')
                    const data = await res.json()
                    this.habitaciones = data        // Actualiza el estado, re-renderiza la tabla
                },

                // Prepara el modal para crear una nueva habitación
                abrirModalNueva() {
                    this.modalTitulo = 'Nueva habitación'
                    this.form = { id: '', numero: '', piso: '', id_categoria_habitacion: '', estado: 'disponible' }     // Limpia el formulario
                    const modal = new Modal(document.getElementById('modalHabitacion'))      // Crea instancia del modal Bootstrap
                    modal.show()
                },

                // Prepara el modal para editar, llenando el form con los datos de la habitación
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

                // POST o PUT - Guarda la habitación (nueva o editada)
                async guardarHabitacion() {
                    // Validación de campos obligatorios antes de enviar
                    if (!this.form.numero || !this.form.piso || !this.form.id_categoria_habitacion){
                        alert('Completá todos los campos')
                    }
                    else{
                        // Si form.id tiene valor → edición (PUT), si está vacío → creación (POST)
                        const url = this.form.id
                            ? 'http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/put_habitaciones.php'
                            : 'http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/post_habitaciones.php'
        
                        // fetch con método POST, enviando los datos como formulario URL-encoded
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams(this.form)    // Convierte el objeto a key=value&key=value
                        })
                        const data = await res.json()       // Parsea la respuesta JSON del backend
        
                        if (data.exito) {
                            // getInstance busca el modal ya abierto para cerrarlo
                            const modal = Modal.getInstance(document.getElementById('modalHabitacion'))
                            modal.hide()
                            await this.cargarHabitaciones()      // Recarga la tabla con los datos actualizados
                        } else {
                            alert(data.mensaje)  // Muestra error del backend (ej: número duplicado)
                        }
                    }
                },

                // Prepara el modal de confirmación de eliminación
                abrirModalEliminar(id) {
                    this.idEliminar = id        // Guarda el ID para usarlo al confirmar
                    const modal = new Modal(document.getElementById('modalEliminar'))
                    modal.show()
                },
                
                // DELETE - Elimina la habitación confirmada
                async eliminarHabitacion() {
                    const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/habitaciones/delete_habitaciones.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ id: this.idEliminar })   // Envía solo el ID
                    })
                    const data = await res.json()

                    if (data.exito) {
                        const modal = Modal.getInstance(document.getElementById('modalEliminar'))
                        modal.hide()
                        await this.cargarHabitaciones()      // Recarga sin la habitación eliminada
                    } else {
                        alert(data.mensaje) // Ej: "tiene reservas asociadas"
                    }
                },
                
                // Resetea todos los filtros a vacío, lo que muestra todas las habitaciones
                limpiarFiltros() {
                    this.filtroNumero = ''
                    this.filtroCategoria = ''
                    this.filtroPiso = ''
                    this.filtroEstado = ''
                }
            }
        }
</script>