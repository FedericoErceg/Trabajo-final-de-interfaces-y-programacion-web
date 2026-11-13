<template>
    <div>
        <!-- Navbar horizontal (en vez de sidebar, porque la grilla del calendario necesita más ancho) -->
        <NavbarComponent />

        <main class="p-4">
            <!-- Encabezado con título y selector de mes -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Calendario de disponibilidad</h1>
                <!-- Controles para navegar entre meses -->
                <div class="d-flex align-items-center gap-3">
                    <!-- @click llama al método mesAnterior que resta un mes y recarga los datos -->
                    <button class="btn btn-outline-secondary btn-sm" @click="mesAnterior">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <!-- nombreMes es una propiedad computed que convierte el número de mes a texto -->
                    <h5 class="mb-0">{{ nombreMes }} {{ anio }}</h5>
                    <button class="btn btn-outline-secondary btn-sm" @click="mesSiguiente">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Leyenda de colores para interpretar la grilla -->
            <div class="d-flex gap-4 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="leyenda-color" style="background-color: #ffffff; border: 1px solid #e5e5e5;"></span>
                    <small>Disponible</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="leyenda-color" style="background-color: #dc3545;"></span>
                    <small>Ocupada</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="leyenda-color" style="background-color: #fd7e14;"></span>
                    <small>Mantenimiento</small>
                </div>
            </div>

            <!-- Componente de la grilla, recibe los datos por props -->
            <!-- :habitaciones, :reservas, etc. son props dinámicas (binding con v-bind) -->
            <GrillaCalendarioComponent
                :habitaciones="datos.habitaciones"
                :reservas="datos.reservas"
                :diasEnMes="datos.diasEnMes"
                :mes="mes"
                :anio="anio"
            />
        </main>
    </div>
</template>

<script>
    // Importación de los componentes que usa esta vista
    import NavbarComponent from '@/components/NavbarComponent.vue'
    import GrillaCalendarioComponent from '@/components/GrillaCalendarioComponent.vue'

    export default {
        // Registro de componentes para poder usarlos en el template
        components: {
            NavbarComponent,
            GrillaCalendarioComponent
        },
        
        // data() retorna el estado reactivo del componente
        data() {
            return {
                mes: new Date().getMonth() + 1,   // Mes actual (getMonth() devuelve 0-11, por eso +1)
                anio: new Date().getFullYear(),     // Año actual
                datos: {
                    habitaciones: [],   // Se llena con el fetch al backend
                    reservas: [],       // Reservas del mes seleccionado
                    diasEnMes: 30       // Valor por defecto, se actualiza con la respuesta del backend
                }
            }
        },

        // Propiedades calculadas: se recalculan automáticamente cuando cambian sus dependencias
        computed: {
            // Convierte el número de mes (1-12) al nombre en español
            nombreMes() {
                const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
                return meses[this.mes - 1]
            }
        },
        
        // mounted() se ejecuta cuando el componente se monta en el DOM
        async mounted() {
            await this.cargarCalendario()   // Carga los datos del mes actual al entrar a la página
        },

        methods: {
            // Hace un fetch GET al backend PHP enviando mes y año como query params
            async cargarCalendario() {
                const res = await fetch(`http://localhost/ProyectoFinal-FedericoErceg/backend/calendario/calendario_back.php?mes=${this.mes}&anio=${this.anio}`)
                const data = await res.json()   // Convierte la respuesta JSON a objeto JavaScript
                this.datos = data               // Actualiza el estado reactivo, lo que re-renderiza la grilla
            },

            // Navega al mes anterior y recarga los datos
            async mesAnterior() {
                if (this.mes === 1) {       // Si estamos en enero
                    this.mes = 12           // Pasamos a diciembre
                    this.anio--             // Del año anterior
                } else {
                    this.mes--
                }
                await this.cargarCalendario()
            },

            // Navega al mes siguiente y recarga los datos
            async mesSiguiente() {
                if (this.mes === 12) {      // Si estamos en diciembre
                    this.mes = 1            // Pasamos a enero
                    this.anio++             // Del año siguiente
                } else {
                    this.mes++
                }
                await this.cargarCalendario()
            }
        }
    }
</script>

<!-- scoped: los estilos aplican solo a este componente, no afectan a otros -->
<style scoped>
    .leyenda-color {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 3px;
    }
</style>