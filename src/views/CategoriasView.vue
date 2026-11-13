<template>
    <!-- Layout con flexbox: sidebar a la izquierda, contenido a la derecha -->
    <div class="d-flex">
        <!-- Componente sidebar reutilizable, currentPage marca cuál link está activo -->
        <SidebarComponent currentPage="categorias" />

        <main class="main-content">
            <!-- Encabezado de la página con título y botón de crear -->
            <div class="content-header">
                <div>
                    <h1>Categorías</h1>
                    <p class="text-muted mb-0">Tipos de habitación y precios</p>
                </div>
                <!-- @click ejecuta el método abrirModalNueva -->
                <button class="btn btn-hotel" @click="abrirModalNueva">
                    <i class="bi bi-plus-lg me-2"></i>Nueva categoría
                </button>
            </div>

            <!-- Componente tabla que recibe las categorías por prop -->
            <!-- @editar y @eliminar escuchan los eventos emitidos por el componente hijo -->
            <TablaCategoriasComponent
                :categorias="categorias"
                @editar="abrirModalEditar"
                @eliminar="abrirModalEliminar"
            />
        </main>
    </div>

    <!-- Modal crear/editar categoría -->
    <!-- v-model:form hace binding bidireccional del formulario entre padre e hijo -->
    <!-- @guardar escucha el evento del botón guardar del modal -->
    <ModalCategoriaComponent
        :titulo="modalTitulo"
        v-model:form="form"
        @guardar="guardarCategoria"
    />

    <!-- Modal de confirmación de eliminación (componente genérico reutilizable) -->
    <!-- @confirmar escucha el clic en el botón "Eliminar" del modal -->
    <ModalEliminarComponent
        modalId="modalEliminar"
        titulo="¿Eliminar categoría?"
        mensaje="No se puede eliminar si tiene habitaciones asociadas."
        @confirmar="eliminarCategoria"
    />
</template>

<script>
    // Importación de componentes
    import SidebarComponent from '@/components/SidebarComponent.vue'
    import TablaCategoriasComponent from '@/components/TablaCategoriasComponent.vue'
    import ModalCategoriaComponent from '@/components/ModalCategoriaComponent.vue'
    import ModalEliminarComponent from '@/components/ModalEliminarComponent.vue'
    import { Modal } from 'bootstrap'

    export default {
        // Registro de componentes usados en el template
        components: {
            SidebarComponent,
            TablaCategoriasComponent,
            ModalCategoriaComponent,
            ModalEliminarComponent
        },

        // Estado reactivo del componente
        data() {
            return {
                categorias: [],         // Array de categorías traídas del backend
                form: {                 // Objeto del formulario, vinculado al modal con v-model
                    id: '',
                    tipo: '',
                    capacidad: '',
                    precio_noche: '',
                    descripcion: ''
                },
                modalTitulo: 'Nueva categoría',     // Cambia entre "Nueva" y "Editar" según la acción
                idEliminar: null                     // Guarda el ID de la categoría a eliminar
            }
        },

        // Se ejecuta al montar el componente en el DOM
        async mounted() {
            await this.cargarCategorias()   // Trae las categorías del backend al cargar la página
        },

        methods: {
            // GET - Trae todas las categorías del backend PHP
            async cargarCategorias() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/categorias_back.php')
                const data = await res.json()
                this.categorias = data      // Actualiza el estado reactivo, re-renderiza la tabla
            },

            // Prepara el modal para crear una nueva categoría
            abrirModalNueva() {
                this.modalTitulo = 'Nueva categoría'
                this.form = { id: '', tipo: '', capacidad: '', precio_noche: '', descripcion: '' }  // Limpia el formulario
                this.modalCategoria = new Modal(document.getElementById('modalCategoria'))           // Crea instancia del modal de Bootstrap
                this.modalCategoria.show()
            },

            // Prepara el modal para editar, llenando el form con los datos de la categoría seleccionada
            abrirModalEditar(cat) {
                this.modalTitulo = 'Editar categoría'
                this.form = {
                    id: cat.id_categoria,
                    tipo: cat.tipo,
                    capacidad: cat.capacidad,
                    precio_noche: cat.precio_noche,
                    descripcion: cat.descripcion
                }
                this.modalCategoria = new Modal(document.getElementById('modalCategoria'))
                this.modalCategoria.show()
            },

            // POST o PUT - Guarda la categoría (nueva o editada)
            async guardarCategoria() {
                // Validación: verifica que los campos obligatorios estén completos
                if (!this.form.tipo || !this.form.capacidad || !this.form.precio_noche){
                    alert('Completá todos los campos')
                }
                else{
                    // Si form.id tiene valor es edición (PUT), si está vacío es creación (POST)
                    const url = this.form.id
                        ? 'http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/put_categorias.php'
                        : 'http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/post_categorias.php'
    
                    // Envía los datos del formulario al backend como POST con URLSearchParams
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams(this.form)    // Convierte el objeto a formato key=value&key=value
                    })
                    const data = await res.json()
    
                    if (data.exito) {
                        this.modalCategoria.hide()      // Cierra el modal
                        await this.cargarCategorias()   // Recarga la tabla con los datos actualizados
                    } else {
                        alert(data.mensaje)             // Muestra el error del backend (ej: tipo duplicado)
                    }
                }
            },

            // Prepara el modal de confirmación de eliminación
            abrirModalEliminar(id) {
                this.idEliminar = id        // Guarda el ID para usarlo cuando se confirme
                this.modalEliminar = new Modal(document.getElementById('modalEliminar'))
                this.modalEliminar.show()
            },
            
            // DELETE - Elimina la categoría confirmada
            async eliminarCategoria() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/delete_categorias.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id: this.idEliminar })   // Envía solo el ID a eliminar
                })
                const data = await res.json()

                if (data.exito) {
                    this.modalEliminar.hide()
                    await this.cargarCategorias() // Recarga la tabla sin la categoría eliminada
                } else {
                    alert(data.mensaje) // Ej: "No se puede eliminar, tiene habitaciones asociadas"
                }
            }
        }
    }
</script>