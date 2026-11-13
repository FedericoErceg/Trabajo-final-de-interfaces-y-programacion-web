<template>
    <!-- Contenedor principal de la grilla del calendario -->
    <div class="calendario-container">
        <!-- Scroll horizontal para meses con muchos días (31 columnas + la de habitación) -->
        <div class="calendario-scroll">
            <table class="tabla-calendario">
                <thead>
                    <tr>
                        <!-- Primera columna fija: nombre de la habitación -->
                        <th class="col-habitacion">Habitación</th>
                        <!-- v-for genera una columna por cada día del mes (1 a 28/29/30/31) -->
                        <!-- diasEnMes es un número, v-for con número genera 1, 2, 3... hasta ese número -->
                        <!-- :class agrega la clase 'dia-hoy' condicionalmente si el día es hoy -->
                        <th v-for="dia in diasEnMes" :key="dia" class="col-dia" :class="{ 'dia-hoy': esHoy(dia) }">
                            {{ dia }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Una fila por cada habitación -->
                    <tr v-for="hab in habitaciones" :key="hab.id_habitacion">
                        <!-- Columna fija con número y tipo de habitación -->
                        <td class="col-habitacion">
                            <strong>{{ hab.numero }}</strong>
                            <small class="text-muted d-block">{{ hab.tipo }}</small>
                        </td>
                        <!-- Una celda por cada día del mes -->
                        <!-- :class llama a claseCelda() que devuelve la clase CSS según el estado -->
                        <!-- Cada celda se colorea: blanco (disponible), rojo (ocupada), naranja (mantenimiento) -->
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
            // Compara si un día de la grilla es el día de hoy
            // Se usa para resaltar la columna del día actual con un fondo dorado
            esHoy(dia) {
                const hoy = new Date()
                return hoy.getDate() === dia && (hoy.getMonth() + 1) === this.mes && hoy.getFullYear() === this.anio
            },

            // Determina qué clase CSS aplicar a cada celda de la grilla
            // Recibe la habitación y el día, devuelve el nombre de la clase

            claseCelda(hab, dia) {
                // Si la habitación está en mantenimiento, toda la fila se pinta naranja
                if (hab.estado_hab === 'mantenimiento') return 'celda-mantenimiento'

                // Arma la fecha en formato YYYY-MM-DD para comparar con las fechas de reservas
                // padStart(2, '0') agrega un 0 adelante si el número tiene un solo dígito (ej: 5 → 05)
                const fecha = `${this.anio}-${String(this.mes).padStart(2, '0')}-${String(dia).padStart(2, '0')}`

                // find() busca si hay una reserva activa para esta habitación en esta fecha
                // La lógica es: fecha_entrada <= fecha actual < fecha_salida
                const reserva = this.reservas.find(r => 
                    r.id_habitacion == hab.id_habitacion &&
                    r.fecha_entrada <= fecha &&
                    r.fecha_salida > fecha
                )

                // Si encontró una reserva: rojo (ocupada), si no → blanco (disponible)
                if (reserva) return 'celda-ocupada'
                return 'celda-disponible'
            }
        }
    }
</script>

<style scoped>
    /* Contenedor con fondo blanco y bordes redondeados */
    .calendario-container {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e5e5e5;
        padding: 16px;
    }

    /* Permite scroll horizontal cuando la grilla es más ancha que la pantalla */
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

    /* Columna de habitación: fija a la izquierda con position sticky */
    /* Así al hacer scroll horizontal, la columna de nombre no se mueve */
    .col-habitacion {
        min-width: 120px;
        max-width: 140px;
        text-align: left;
        padding: 8px;
        background: #f8f9fa;
        position: sticky;
        left: 0;
        z-index: 1;   /* Se pone por encima de las celdas al hacer scroll */
    }

    /* Ancho mínimo de cada celda de día */
    .col-dia {
        min-width: 32px;
    }

    /* Resaltado del día actual con el color dorado del hotel */
    .dia-hoy {
        background-color: #8b7355;
        color: #fff;
        font-weight: 700;
    }

    /* Colores de estado de cada celda */
    .celda-disponible {
        background-color: #ffffff;      /* Blanco = disponible */
    }

    .celda-ocupada {
        background-color: #dc3545;      /* Rojo = tiene reserva activa */
    }

    .celda-mantenimiento {
        background-color: #fd7e14;      /* Naranja = habitación en mantenimiento */
    }
</style>