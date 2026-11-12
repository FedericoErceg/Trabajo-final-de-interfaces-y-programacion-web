<template>
    <div>
        <NavbarComponent />

        <main class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Calendario de disponibilidad</h1>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary btn-sm" @click="mesAnterior">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h5 class="mb-0">{{ nombreMes }} {{ anio }}</h5>
                    <button class="btn btn-outline-secondary btn-sm" @click="mesSiguiente">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Leyenda -->
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
    import NavbarComponent from '@/components/NavbarComponent.vue'
    import GrillaCalendarioComponent from '@/components/GrillaCalendarioComponent.vue'

    export default {
        components: {
            NavbarComponent,
            GrillaCalendarioComponent
        },
        data() {
            return {
                mes: new Date().getMonth() + 1,
                anio: new Date().getFullYear(),
                datos: {
                    habitaciones: [],
                    reservas: [],
                    diasEnMes: 30
                }
            }
        },
        computed: {
            nombreMes() {
                const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
                return meses[this.mes - 1]
            }
        },
        async mounted() {
            await this.cargarCalendario()
        },
        methods: {
            async cargarCalendario() {
                const res = await fetch(`http://localhost/ProyectoFinal-FedericoErceg/backend/calendario/calendario_back.php?mes=${this.mes}&anio=${this.anio}`)
                const data = await res.json()
                this.datos = data
            },
            async mesAnterior() {
                if (this.mes === 1) {
                    this.mes = 12
                    this.anio--
                } else {
                    this.mes--
                }
                await this.cargarCalendario()
            },
            async mesSiguiente() {
                if (this.mes === 12) {
                    this.mes = 1
                    this.anio++
                } else {
                    this.mes++
                }
                await this.cargarCalendario()
            }
        }
    }
</script>

<style scoped>
    .leyenda-color {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 3px;
    }
</style>