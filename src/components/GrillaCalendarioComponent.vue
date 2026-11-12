<template>
    <div class="calendario-container">
        <div class="calendario-scroll">
            <table class="tabla-calendario">
                <thead>
                    <tr>
                        <th class="col-habitacion">Habitación</th>
                        <th v-for="dia in diasEnMes" :key="dia" class="col-dia" :class="{ 'dia-hoy': esHoy(dia) }">
                            {{ dia }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="hab in habitaciones" :key="hab.id_habitacion">
                        <td class="col-habitacion">
                            <strong>{{ hab.numero }}</strong>
                            <small class="text-muted d-block">{{ hab.tipo }}</small>
                        </td>
                        <td v-for="dia in diasEnMes" :key="dia" 
                            class="col-dia"
                            :class="claseCelda(hab, dia)">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['habitaciones', 'reservas', 'diasEnMes', 'mes', 'anio'],
        methods: {
            esHoy(dia) {
                const hoy = new Date()
                return hoy.getDate() === dia && (hoy.getMonth() + 1) === this.mes && hoy.getFullYear() === this.anio
            },
            claseCelda(hab, dia) {
                if (hab.estado_hab === 'mantenimiento') return 'celda-mantenimiento'

                const fecha = `${this.anio}-${String(this.mes).padStart(2, '0')}-${String(dia).padStart(2, '0')}`

                const reserva = this.reservas.find(r => 
                    r.id_habitacion == hab.id_habitacion &&
                    r.fecha_entrada <= fecha &&
                    r.fecha_salida > fecha
                )

                if (reserva) return 'celda-ocupada'
                return 'celda-disponible'
            }
        }
    }
</script>

<style scoped>
    .calendario-container {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e5e5e5;
        padding: 16px;
    }

    .calendario-scroll {
        overflow-x: auto;
    }

    .tabla-calendario {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .tabla-calendario th,
    .tabla-calendario td {
        border: 1px solid #e5e5e5;
        text-align: center;
        padding: 6px 4px;
    }

    .col-habitacion {
        min-width: 120px;
        max-width: 140px;
        text-align: left;
        padding: 8px;
        background: #f8f9fa;
        position: sticky;
        left: 0;
        z-index: 1;
    }

    .col-dia {
        min-width: 32px;
    }

    .dia-hoy {
        background-color: #8b7355;
        color: #fff;
        font-weight: 700;
    }

    .celda-disponible {
        background-color: #ffffff;
    }

    .celda-ocupada {
        background-color: #dc3545;
    }

    .celda-mantenimiento {
        background-color: #fd7e14;
    }
</style>