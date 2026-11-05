<template>
    <div class="d-flex">
        <SidebarComponent currentPage="categorias" />

        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1>Categorías</h1>
                    <p class="text-muted mb-0">Tipos de habitación y precios</p>
                </div>
                <button class="btn btn-hotel" @click="abrirModalNueva">
                    <i class="bi bi-plus-lg me-2"></i>Nueva categoría
                </button>
            </div>

            <TablaCategoriasComponent
                :categorias="categorias"
                @editar="abrirModalEditar"
                @eliminar="abrirModalEliminar"
            />
        </main>
    </div>

    <ModalCategoriaComponent
        :titulo="modalTitulo"
        v-model:form="form"
        @guardar="guardarCategoria"
    />

    <ModalEliminarComponent
        modalId="modalEliminar"
        titulo="¿Eliminar categoría?"
        mensaje="No se puede eliminar si tiene habitaciones asociadas."
        @confirmar="eliminarCategoria"
    />
</template>

<script>
    import SidebarComponent from '@/components/SidebarComponent.vue'
    import TablaCategoriasComponent from '@/components/TablaCategoriasComponent.vue'
    import ModalCategoriaComponent from '@/components/ModalCategoriaComponent.vue'
    import ModalEliminarComponent from '@/components/ModalEliminarComponent.vue'
    import { Modal } from 'bootstrap'

    export default {
        components: {
            SidebarComponent,
            TablaCategoriasComponent,
            ModalCategoriaComponent,
            ModalEliminarComponent
        },
        data() {
            return {
                categorias: [],
                form: {
                    id: '',
                    tipo: '',
                    capacidad: '',
                    precio_noche: '',
                    descripcion: ''
                },
                modalTitulo: 'Nueva categoría',
                idEliminar: null
            }
        },
        async mounted() {
            await this.cargarCategorias()
        },
        methods: {
            async cargarCategorias() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/categorias_back.php')
                const data = await res.json()
                this.categorias = data
            },
            abrirModalNueva() {
                this.modalTitulo = 'Nueva categoría'
                this.form = { id: '', tipo: '', capacidad: '', precio_noche: '', descripcion: '' }
                this.modalCategoria = new Modal(document.getElementById('modalCategoria'))
                this.modalCategoria.show()
            },
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
            async guardarCategoria() {
                const url = this.form.id
                    ? 'http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/put_categorias.php'
                    : 'http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/post_categorias.php'

                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(this.form)
                })
                const data = await res.json()

                if (data.exito) {
                    this.modalCategoria.hide()
                    await this.cargarCategorias()
                } else {
                    alert(data.mensaje)
                }
            },
            abrirModalEliminar(id) {
                this.idEliminar = id
                this.modalEliminar = new Modal(document.getElementById('modalEliminar'))
                this.modalEliminar.show()
            },
            async eliminarCategoria() {
                const res = await fetch('http://localhost/ProyectoFinal-FedericoErceg/backend/categorias/delete_categorias.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id: this.idEliminar })
                })
                const data = await res.json()

                if (data.exito) {
                    this.modalEliminar.hide()
                    await this.cargarCategorias()
                } else {
                    alert(data.mensaje)
                }
            }
        }
    }
</script>